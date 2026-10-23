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
        // 1. Tool & Book Donations
        Schema::create('tool_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('item_type'); // textbook, drawing_tools, calculators_electronics, lab_hardware, workshop_safety, other
            $table->string('department');
            $table->string('semester_suitability')->default('All Semesters');
            $table->string('condition')->default('gently_used'); // brand_new, like_new, gently_used, fair
            $table->text('description');
            $table->string('pickup_method')->default('campus_locker'); // campus_locker, handover_patan, courier
            $table->string('pickup_location')->nullable();
            $table->unsignedInteger('estimated_value')->default(500); // Estimated INR saved for student
            $table->string('photo_path')->nullable();
            $table->text('donor_note')->nullable();
            $table->string('status')->default('available'); // available, claimed, handed_over, archived
            $table->foreignId('claimed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('claimed_at')->nullable();
            $table->dateTime('handed_over_at')->nullable();
            $table->timestamps();
        });

        // 2. Donation Requests by Students
        Schema::create('tool_donation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_donation_id')->constrained('tool_donations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('reason');
            $table->string('status')->default('pending'); // pending, accepted, declined, cancelled
            $table->text('donor_response')->nullable();
            $table->timestamps();
        });

        // 3. Pay-It-Forward Gratitude Stories
        Schema::create('tool_donation_gratitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_donation_id')->constrained('tool_donations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->string('photo_path')->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_donation_gratitudes');
        Schema::dropIfExists('tool_donation_requests');
        Schema::dropIfExists('tool_donations');
    }
};
