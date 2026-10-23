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
        Schema::create('campus_memory_spots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('academic'); // academic, workshop, recreation, landmark
            $table->text('description');
            $table->float('x_percent')->default(50.0); // 0-100% position on SVG map
            $table->float('y_percent')->default(50.0);
            $table->string('icon')->default('building');
            $table->string('then_photo_path');
            $table->string('then_year')->default('1988');
            $table->string('then_title')->default('Archival Heritage View');
            $table->string('now_photo_path');
            $table->string('now_year')->default('2026');
            $table->string('now_title')->default('Modern Upgraded View');
            $table->text('fun_fact')->nullable();
            $table->integer('order_num')->default(0);
            $table->timestamps();
        });

        Schema::create('campus_memory_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_memory_spot_id')->constrained('campus_memory_spots')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name');
            $table->integer('batch_year')->default(2015);
            $table->string('department')->default('Mechanical Engineering');
            $table->text('story');
            $table->string('photo_path')->nullable();
            $table->integer('likes_count')->default(0);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campus_memory_stories');
        Schema::dropIfExists('campus_memory_spots');
    }
};
