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
        Schema::create('e_library_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category'); // 'ebook', 'capstone_project', 'lab_manual'
            $table->string('department'); // 'Computer', 'Mechanical', 'Electrical', 'Civil', 'General'
            $table->unsignedTinyInteger('semester')->nullable(); // 1 - 6
            $table->string('academic_year')->nullable(); // e.g. '2023-24'
            $table->string('author_or_team')->nullable(); // Author name, Student Project Team, or Faculty
            $table->string('guide_faculty')->nullable(); // Faculty Guide for capstone projects
            $table->string('subject_code')->nullable(); // GTU subject code (e.g. 4330701)
            $table->string('subject_name')->nullable();
            $table->string('tech_stack')->nullable(); // e.g. 'Python, ESP32, MQTT'
            $table->text('description')->nullable(); // Abstract / Synopsis / Overview
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_format')->default('PDF'); // PDF, EPUB, DOCX, ZIP
            $table->string('file_size')->nullable(); // e.g. '3.5 MB'
            $table->string('external_url')->nullable(); // Drive or GTU portal reference
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_library_items');
    }
};
