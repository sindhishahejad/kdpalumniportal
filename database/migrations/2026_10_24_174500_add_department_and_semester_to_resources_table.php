<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->string('department')->nullable()->after('category');
            $table->unsignedTinyInteger('semester')->nullable()->after('department');
            $table->string('subject')->nullable()->after('semester');
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['department', 'semester', 'subject']);
        });
    }
};
