<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // exam, term, circular, holiday
            $table->string('semester_scope')->default('All Semesters');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->string('attachment_url')->nullable();
            $table->boolean('is_important')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_schedules');
    }
};
