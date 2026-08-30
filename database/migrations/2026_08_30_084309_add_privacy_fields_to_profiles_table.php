<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('is_phone_public')->default(false)->after('phone');
            $table->boolean('is_email_public')->default(false)->after('is_phone_public');
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['is_phone_public', 'is_email_public']);
        });
    }
};