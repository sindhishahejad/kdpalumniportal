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
        Schema::table('job_referrals', function (Blueprint $table) {
            $table->string('department')->nullable()->after('candidate_linkedin');
            $table->string('enrollment_no')->nullable()->after('department');
            $table->string('graduation_year')->nullable()->after('enrollment_no');
            $table->string('cpi')->nullable()->after('graduation_year');
            $table->string('portfolio_url')->nullable()->after('cpi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_referrals', function (Blueprint $table) {
            $table->dropColumn([
                'department',
                'enrollment_no',
                'graduation_year',
                'cpi',
                'portfolio_url',
            ]);
        });
    }
};
