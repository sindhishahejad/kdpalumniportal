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
        // 1. Extend posts table with interactive challenge fields
        Schema::table('posts', function (Blueprint $table) {
            $table->string('question_type')->default('discussion')->after('type'); // discussion, mcq, fill_blank, coding
            $table->string('image_path')->nullable()->after('question_type');
            $table->json('options')->nullable()->after('image_path'); // Array of MCQ choices
            $table->text('correct_answer')->nullable()->after('options'); // Correct MCQ option or Fill-blank answers
            $table->text('explanation')->nullable()->after('correct_answer'); // Solution notes shown after answering
            $table->text('starter_code')->nullable()->after('explanation'); // Boilerplate for coding challenges
            $table->string('programming_language')->nullable()->after('starter_code'); // python, c, cpp, java, javascript, sql, php
            $table->string('difficulty')->default('medium')->after('programming_language'); // easy, medium, hard
            $table->json('test_cases')->nullable()->after('difficulty'); // Optional test cases
        });

        // 2. Create post_submissions table to record & verify student attempts
        Schema::create('post_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('answer'); // Selected option index/text, filled blank string, or submitted code
            $table->boolean('is_correct')->default(false);
            $table->text('feedback')->nullable(); // Automated verification report
            $table->timestamps();

            $table->index(['post_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_submissions');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'question_type',
                'image_path',
                'options',
                'correct_answer',
                'explanation',
                'starter_code',
                'programming_language',
                'difficulty',
                'test_cases',
            ]);
        });
    }
};
