<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('department'); // e.g. Computer, Mechanical, Electrical, Civil, Applied Mechanics, General
            $table->string('qualification');
            $table->integer('experience_years')->nullable();
            $table->string('specialization')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_hod')->default(false);
            $table->integer('order_num')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_members');
    }
};
