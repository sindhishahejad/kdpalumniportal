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
        // 1. Award Cycles
        Schema::create('award_cycles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('year');
            $table->string('title');
            $table->string('theme')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('nominations_open'); // draft, nominations_open, voting_open, concluded
            $table->dateTime('nominations_start_at')->nullable();
            $table->dateTime('nominations_end_at')->nullable();
            $table->dateTime('voting_start_at')->nullable();
            $table->dateTime('voting_end_at')->nullable();
            $table->date('ceremony_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Award Categories
        Schema::create('award_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('award_cycle_id')->constrained('award_cycles')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('eligibility_criteria')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });

        // 3. Award Nominations
        Schema::create('award_nominations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('award_cycle_id')->constrained('award_cycles')->cascadeOnDelete();
            $table->foreignId('award_category_id')->constrained('award_categories')->cascadeOnDelete();
            $table->foreignId('nominee_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('nominator_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nominee_name');
            $table->string('nominee_email')->nullable();
            $table->string('nominee_phone')->nullable();
            $table->string('nominee_department');
            $table->unsignedInteger('nominee_batch_year');
            $table->string('current_organization')->nullable();
            $table->string('designation')->nullable();
            $table->text('achievement_summary');
            $table->text('impact_statement')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->boolean('is_winner')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // 4. Award Votes
        Schema::create('award_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('award_cycle_id')->constrained('award_cycles')->cascadeOnDelete();
            $table->foreignId('award_category_id')->constrained('award_categories')->cascadeOnDelete();
            $table->foreignId('award_nomination_id')->constrained('award_nominations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['award_cycle_id', 'award_category_id', 'user_id'], 'award_vote_user_category_unique');
        });

        // 5. Hall of Fame Inductees
        Schema::create('hall_of_fame_inductees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('award_cycle_id')->nullable()->constrained('award_cycles')->nullOnDelete();
            $table->foreignId('award_category_id')->nullable()->constrained('award_categories')->nullOnDelete();
            $table->foreignId('award_nomination_id')->nullable()->constrained('award_nominations')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('department');
            $table->unsignedInteger('batch_year');
            $table->unsignedInteger('induction_year');
            $table->string('award_title');
            $table->text('citation');
            $table->string('current_role')->nullable();
            $table->string('company')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hall_of_fame_inductees');
        Schema::dropIfExists('award_votes');
        Schema::dropIfExists('award_nominations');
        Schema::dropIfExists('award_categories');
        Schema::dropIfExists('award_cycles');
    }
};
