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
        Schema::create('whatsapp_broadcast_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('recipient_phone');
            $table->string('recipient_name')->nullable();
            $table->string('alert_type'); // document_ready, job_referral, mentorship, event_ticket, tool_handover, admin_broadcast, emergency_sos
            $table->text('message');
            $table->string('channel')->default('direct_click'); // direct_click, cloud_api, twilio, log
            $table->string('status')->default('sent'); // sent, delivered, opened, queued
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_broadcast_logs');
    }
};
