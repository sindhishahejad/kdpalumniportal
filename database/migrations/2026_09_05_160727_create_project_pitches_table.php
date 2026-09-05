<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_pitches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // The student pitching
            $table->string('title');
            $table->text('description');
            $table->string('tech_stack'); // e.g., Laravel, React Native, ESP32
            $table->string('assistance_needed'); // e.g., Code Review, Relay Modules, Cloud Hosting
            $table->enum('status', ['open', 'in_progress', 'completed'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_pitches');
    }
};