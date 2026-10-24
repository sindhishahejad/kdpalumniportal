<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placement_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sector');
            $table->string('package_range')->nullable();
            $table->string('roles_offered')->nullable();
            $table->string('hiring_departments')->nullable(); // e.g. "Civil, Mechanical, Electrical"
            $table->string('website_url')->nullable();
            $table->string('logo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_num')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placement_partners');
    }
};
