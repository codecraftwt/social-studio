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
            $table->dropColumn(['address', 'postal_code', 'current_location', 'mobile', 'profile_pic']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->after('password');
            $table->string('postal_code')->nullable()->after('address');
            $table->string('current_location')->nullable()->after('postal_code');
            $table->string('mobile')->nullable()->after('current_location');
            $table->string('profile_pic')->nullable()->after('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address', 'postal_code', 'current_location', 'mobile', 'profile_pic']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('current_location')->nullable();
            $table->string('mobile')->nullable();
            $table->string('profile_pic')->nullable();
        });
    }
};
