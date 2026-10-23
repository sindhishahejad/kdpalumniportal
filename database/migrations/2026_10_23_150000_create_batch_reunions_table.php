<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batch_reunions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('batch_year')->default('All Batches');
            $table->string('department')->nullable();
            $table->dateTime('event_date');
            $table->string('venue');
            $table->string('city')->default('Patan');
            $table->text('description');
            $table->string('milestone_tag')->nullable();
            $table->string('banner_image')->nullable();
            $table->integer('fee_per_person')->default(0);
            $table->string('whatsapp_group_link')->nullable();
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed
            $table->timestamps();
        });

        Schema::create('reunion_rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_reunion_id')->constrained('batch_reunions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('attending'); // attending, maybe, declined
            $table->integer('guests_count')->default(0);
            $table->string('carpool_note')->nullable();
            $table->text('nostalgic_quote')->nullable();
            $table->timestamps();

            $table->unique(['batch_reunion_id', 'user_id']);
        });

        Schema::create('reunion_memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_reunion_id')->constrained('batch_reunions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('photo_path')->nullable();
            $table->text('caption');
            $table->string('year_taken')->nullable();
            $table->integer('likes_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reunion_memories');
        Schema::dropIfExists('reunion_rsvps');
        Schema::dropIfExists('batch_reunions');
    }
};
