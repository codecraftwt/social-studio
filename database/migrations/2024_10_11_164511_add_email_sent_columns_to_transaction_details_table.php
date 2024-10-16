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
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->timestamp('expiry_soon_email_sent_at')->nullable()->after('plan_expiry_date');
            $table->timestamp('expired_email_sent_at')->nullable()->after('expiry_soon_email_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('expiry_soon_email_sent_at');
            $table->dropColumn('expired_email_sent_at');
        });
    }
};
