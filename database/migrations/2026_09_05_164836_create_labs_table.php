<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Microprocessor & Hardware Lab
            $table->string('department'); // e.g., Computer Engineering, Electrical Engineering
            $table->string('faculty_in_charge'); // e.g., Prof. A. B. Patel
            $table->string('location'); // e.g., Room 104, Main Block
            $table->text('equipment_list'); // comma-separated or JSON list of hardware
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labs');
    }
};