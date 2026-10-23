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
        Schema::table('users', function (Blueprint $table) {
            $table->string('degree')->nullable()->after('entry_no');
            $table->string('year_joining')->nullable()->after('department');
            $table->string('graduation_year')->nullable()->after('year_joining');
            $table->string('company')->nullable()->after('graduation_year');
            $table->string('designation')->nullable()->after('company');
            $table->string('work_industry')->nullable()->after('designation');
            $table->text('skills')->nullable()->after('work_industry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'degree',
                'year_joining',
                'graduation_year',
                'company',
                'designation',
                'work_industry',
                'skills',
            ]);
        });
    }
};
