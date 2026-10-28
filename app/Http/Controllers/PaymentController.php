<?php

namespace App\Http\Controllers;

use App\Models\BatchReunion;
use App\Models\Donation;
use App\Models\ProjectPitch;
use App\Models\ReunionRsvp;
use App\Models\ToolDonation;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected RazorpayService $razorpay;

    public function __construct(RazorpayService $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    /**
     * Initialize a Razorpay payment order for any portal donation or fee.
     */
    public function createOrder(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10', 'max:1000000'],
            'purpose' => ['required', 'string', 'in:giving_back,pitch_seed,tool_bank_sponsor,reunion_fee'],
            'campaign' => ['nullable', 'string', 'max:255'],
            'payable_type' => ['nullable', 'string', 'max:100'],
            'payable_id' => ['nullable', 'integer'],
            'is_anonymous' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
            'guests_count' => ['nullable', 'integer', 'min:0', 'max:20'],
        ]);

        $user = Auth::user();
        $amount = (float) $request->amount;
        $purpose = $request->purpose;
        $campaignTitle = $request->campaign ?: 'General Campus Development Fund';
        $payableType = null;
        $payableId = null;

        // Contextual routing based on donation touchpoint
        switch ($purpose) {
            case 'pitch_seed':
                $pitch = ProjectPitch::findOrFail($request->payable_id);
                $payableType = ProjectPitch::class;
                $payableId = $pitch->id;
                $campaignTitle = "Student Innovation Grant: {$pitch->title}";
                break;

            case 'tool_bank_sponsor':
                $payableType = ToolDonation::class;
                $payableId = $request->payable_id ?: null;
                $campaignTitle = $request->campaign ?: 'Pay-It-Forward Tool & Book Bank Sponsorship';
                break;

            case 'reunion_fee':
                $reunion = BatchReunion::findOrFail($request->payable_id);
                $payableType = BatchReunion::class;
                $payableId = $reunion->id;
                $campaignTitle = "Batch Reunion Fee: {$reunion->title}";
                break;

            case 'giving_back':
            default:
                $campaignTitle = $request->campaign ?: 'General Campus Development Fund';
                break;
        }

        $receiptId = 'KDP-ORD-' . strtoupper(Str::random(10));

        // 1. Create a pending donation record
        $donation = Donation::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'campaign' => $campaignTitle,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'notes' => $request->notes,
            'payment_method' => 'Razorpay (UPI / Net Banking / Card)',
            'transaction_id' => 'KDP-TXN-' . strtoupper(Str::random(8)),
            'status' => 'pending',
            'payable_type' => $payableType,
            'payable_id' => $payableId,
        ]);

        // 2. Create Razorpay order via RazorpayService
        $orderData = $this->razorpay->createOrder($amount, $receiptId, [
            'donation_id' => (string) $donation->id,
            'purpose' => $purpose,
            'campaign' => Str::limit($campaignTitle, 30),
            'donor_name' => $user->name,
        ]);

        // Attach Razorpay order ID
        $donation->update([
            'razorpay_order_id' => $orderData['order_id'],
        ]);

        return response()->json([
            'success' => true,
            'donation_id' => $donation->id,
            'order_id' => $orderData['order_id'],
            'amount' => $amount,
            'amount_paise' => $orderData['amount_paise'],
            'currency' => $orderData['currency'],
            'key_id' => $this->razorpay->getKeyId(),
            'merchant_name' => $this->razorpay->getMerchantName(),
            'theme_color' => $this->razorpay->getThemeColor(),
            'campaign' => $campaignTitle,
            'is_simulated' => $orderData['is_simulated'] ?? false,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ],
            'receipt_id' => $receiptId,
        ]);
    }

    /**
     * Verify payment signature and finalize donation & side-effects.
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        $request->validate([
            'donation_id' => ['required', 'exists:donations,id'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'guests_count' => ['nullable', 'integer', 'min:0'],
        ]);

        $donation = Donation::findOrFail($request->donation_id);

        // Security check: Verify signature
        $isValid = $this->razorpay->verifyPaymentSignature(
            $request->razorpay_order_id,
            $request->razorpay_payment_id,
            $request->razorpay_signature
        );

        if (!$isValid) {
            Log::warning("Razorpay verification failed for donation #{$donation->id}. Order: {$request->razorpay_order_id}");
            return response()->json([
                'success' => false,
                'message' => 'Invalid Razorpay payment signature. Payment could not be verified.',
            ], 422);
        }

        // Finalize completed status in a database transaction
        DB::transaction(function () use ($donation, $request) {
            $paymentMethod = $request->payment_method ?: 'Razorpay UPI/Card';

            $donation->update([
                'status' => 'completed',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'payment_method' => $paymentMethod,
            ]);

            // Side-effect: If this was for a student innovation pitch
            if ($donation->payable_type === ProjectPitch::class && $donation->payable_id) {
                $pitch = ProjectPitch::find($donation->payable_id);
                if ($pitch) {
                    $pitch->increment('funds_raised', $donation->amount);
                }
            }

            // Side-effect: If this was for a batch reunion attendee RSVP fee
            if ($donation->payable_type === BatchReunion::class && $donation->payable_id) {
                $reunion = BatchReunion::find($donation->payable_id);
                if ($reunion) {
                    $guestsCount = (int) ($request->guests_count ?? 0);
                    ReunionRsvp::updateOrCreate([
                        'batch_reunion_id' => $reunion->id,
                        'user_id' => $donation->user_id,
                    ], [
                        'status' => 'attending',
                        'guests_count' => $guestsCount,
                        'payment_status' => 'paid',
                        'amount_paid' => $donation->amount,
                        'donation_id' => $donation->id,
                        'carpool_note' => 'Paid via Razorpay',
                        'nostalgic_quote' => $donation->notes ?: 'Excited to attend the reunion!',
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Thank you! Your contribution of ₹" . number_format($donation->amount, 2) . " to '{$donation->campaign}' has been successfully received via Razorpay.",
            'donation_id' => $donation->id,
            'transaction_id' => $donation->transaction_id,
            'razorpay_payment_id' => $donation->razorpay_payment_id,
            'amount' => (float) $donation->amount,
            'campaign' => $donation->campaign,
            'receipt_url' => route('donations.receipt', $donation->id),
        ]);
    }
}
