<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regional_chapters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('city');
            $table->string('state')->default('Gujarat');
            $table->string('country')->default('India');
            $table->string('region_type')->default('gujarat'); // gujarat, national, global
            $table->text('description')->nullable();
            $table->foreignId('lead_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('meeting_venue')->nullable();
            $table->string('whatsapp_group_link')->nullable();
            $table->string('contact_email')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('chapter_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_chapter_id')->constrained('regional_chapters')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('member'); // member, coordinator, lead
            $table->timestamps();

            $table->unique(['regional_chapter_id', 'user_id']);
        });

        Schema::create('chapter_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_chapter_id')->constrained('regional_chapters')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->dateTime('event_date');
            $table->string('venue');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_activities');
        Schema::dropIfExists('chapter_memberships');
        Schema::dropIfExists('regional_chapters');
    }
};
