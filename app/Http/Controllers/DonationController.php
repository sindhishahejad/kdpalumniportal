<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class DonationController extends Controller
{
    /**
     * Display the Giving Back & Crowdfunding Hub.
     */
    public function index()
    {
        $campaignDefinitions = [
            [
                'id' => 'scholarship',
                'title' => 'Student Scholarship Fund',
                'category' => 'Student Aid',
                'target' => 150000,
                'description' => 'Tuition grants, technical kits, and hostel fee assistance for deserving polytechnic students.',
                'badge_color' => 'bg-emerald-100 text-emerald-800',
                'bar_color' => 'bg-emerald-600',
            ],
            [
                'id' => 'lab',
                'title' => 'Computer Engineering Lab Upgrade',
                'category' => 'Infrastructure',
                'target' => 200000,
                'description' => 'Procuring high-performance CAD/coding workstations, GPU clusters, and modern networking switches.',
                'badge_color' => 'bg-blue-100 text-blue-800',
                'bar_color' => 'bg-blue-600',
            ],
            [
                'id' => 'library',
                'title' => 'Library & Digital Resource Center',
                'category' => 'Academics',
                'target' => 75000,
                'description' => 'Subscriptions to IEEE research papers, AI/ML reference handbooks, and e-book kiosks.',
                'badge_color' => 'bg-amber-100 text-amber-800',
                'bar_color' => 'bg-amber-600',
            ],
            [
                'id' => 'general',
                'title' => 'General Campus Development Fund',
                'category' => 'Campus Life',
                'target' => 300000,
                'description' => 'Campus solar illumination, sports equipment, Robocon lab tools, and seminar hall audio.',
                'badge_color' => 'bg-purple-100 text-purple-800',
                'bar_color' => 'bg-purple-600',
            ],
        ];

        // Attach live calculations to each campaign
        $campaigns = array_map(function ($camp) {
            $raised = Donation::where('campaign', $camp['title'])
                ->where('status', 'completed')
                ->sum('amount');

            $camp['raised'] = (float) $raised;
            $camp['percent'] = min(100, round(($raised / $camp['target']) * 100, 1));
            $camp['donor_count'] = Donation::where('campaign', $camp['title'])
                ->where('status', 'completed')
                ->count();

            return $camp;
        }, $campaignDefinitions);

        $totalRaised = Donation::where('status', 'completed')->sum('amount');
        $totalDonorsCount = Donation::where('status', 'completed')->distinct('user_id')->count('user_id');

        // Recent public contributions for the Wall of Contributors
        $donations = Donation::with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(12)
            ->get();

        // Authenticated user's personal donation history
        $userDonations = Auth::user()->donations()->latest()->get();

        return view('donations.index', compact('campaigns', 'donations', 'userDonations', 'totalRaised', 'totalDonorsCount'));
    }

    /**
     * Store a new contribution and generate receipt reference.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:50', 'max:1000000'],
            'campaign' => ['required', 'string', 'max:255'],
            'is_anonymous' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        $donation = Donation::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'campaign' => $request->campaign,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'notes' => $request->notes,
            'payment_method' => $request->payment_method ?? 'UPI / Net Banking',
            'transaction_id' => 'KDP-TXN-' . strtoupper(Str::random(8)),
            'status' => 'completed',
        ]);

        return back()->with('success', "Thank you! Your generous contribution of ₹" . number_format($donation->amount, 2) . " to '{$donation->campaign}' was received. Your official 80G tax receipt is now available below.");
    }

    /**
     * Download the official 80G tax deduction receipt as PDF.
     */
    public function downloadReceipt(Donation $donation)
    {
        $user = Auth::user();

        // Only the donor or an administrator can download the receipt
        if ($donation->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to access this donation receipt.');
        }

        $donation->load('user.profile');
        $amountInWords = self::amountInWords($donation->amount);
        $receiptNo = 'KDP-REC-' . str_pad($donation->id, 5, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('donations.receipt', compact('donation', 'amountInWords', 'receiptNo'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download("KDP-Donation-Receipt-{$donation->transaction_id}.pdf");
    }

    /**
     * Convert currency float to Indian numbering words.
     */
    public static function amountInWords(float $number): string
    {
        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
            30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        ];
        
        $intNumber = (int) $number;
        if ($intNumber === 0) return 'Zero Rupees Only';
        
        $result = '';
        
        // Lakhs
        if ($intNumber >= 100000) {
            $lakhs = (int)($intNumber / 100000);
            $result .= self::convertTwoDigits($lakhs, $words) . ' Lakh ';
            $intNumber %= 100000;
        }
        
        // Thousands
        if ($intNumber >= 1000) {
            $thousands = (int)($intNumber / 1000);
            $result .= self::convertTwoDigits($thousands, $words) . ' Thousand ';
            $intNumber %= 1000;
        }
        
        // Hundreds
        if ($intNumber >= 100) {
            $hundreds = (int)($intNumber / 100);
            $result .= $words[$hundreds] . ' Hundred ';
            $intNumber %= 100;
        }
        
        if ($intNumber > 0) {
            $result .= self::convertTwoDigits($intNumber, $words) . ' ';
        }
        
        return trim($result) . ' Rupees Only';
    }

    private static function convertTwoDigits(int $num, array $words): string
    {
        if ($num < 20) {
            return $words[$num] ?? '';
        }
        $tens = (int)($num / 10) * 10;
        $units = $num % 10;
        return trim(($words[$tens] ?? '') . ' ' . ($words[$units] ?? ''));
    }
}
