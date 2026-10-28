<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add Razorpay and Polymorphic fields to donations table
        Schema::table('donations', function (Blueprint $table) {
            $table->string('razorpay_order_id')->nullable()->after('transaction_id');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            $table->nullableMorphs('payable'); // creates payable_type and payable_id with composite index
            $table->index('razorpay_order_id');
        });

        // 2. Add funding goal & funds raised to student project pitches
        Schema::table('project_pitches', function (Blueprint $table) {
            $table->decimal('funding_goal', 10, 2)->default(0)->after('assistance_needed');
            $table->decimal('funds_raised', 10, 2)->default(0)->after('funding_goal');
        });

        // 3. Add payment tracking to batch reunion RSVPs
        Schema::table('reunion_rsvps', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('nostalgic_quote'); // unpaid, paid, exempt
            $table->decimal('amount_paid', 10, 2)->default(0)->after('payment_status');
            $table->foreignId('donation_id')->nullable()->after('amount_paid')->constrained('donations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reunion_rsvps', function (Blueprint $table) {
            $table->dropForeign(['donation_id']);
            $table->dropColumn(['payment_status', 'amount_paid', 'donation_id']);
        });

        Schema::table('project_pitches', function (Blueprint $table) {
            $table->dropColumn(['funding_goal', 'funds_raised']);
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->dropMorphs('payable');
            $table->dropIndex(['razorpay_order_id']);
            $table->dropColumn([
                'razorpay_order_id',
                'razorpay_payment_id',
                'razorpay_signature',
            ]);
        });
    }
};
