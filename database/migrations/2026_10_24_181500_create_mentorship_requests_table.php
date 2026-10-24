<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentorship_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentorship_listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mentee_id')->constrained('users')->cascadeOnDelete();
            $table->string('topic')->nullable();
            $table->text('message');
            $table->string('status')->default('pending'); // pending, accepted, completed, declined
            $table->string('meeting_link')->nullable(); // Zoom, Google Meet, or WhatsApp Click-to-chat
            $table->string('session_time')->nullable(); // e.g. "Saturdays 5:00 PM" or "2026-11-01 16:00"
            $table->text('mentor_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentorship_requests');
    }
};
