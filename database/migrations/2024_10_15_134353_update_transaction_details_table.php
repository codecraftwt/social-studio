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
            // Make the transaction_id and payment_screenshot nullable
            $table->string('transaction_id')->nullable()->change();
            $table->decimal('amount', 10, 2)->nullable()->change(); // If you don't have an 'amount' column, add this line
            $table->string('payment_screenshot')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Revert changes in the down method
            $table->string('transaction_id')->nullable(false)->change();
            $table->decimal('amount', 10, 2)->nullable(false)->change(); // If you don't have an 'amount' column, remove this line
            $table->string('payment_screenshot')->nullable(false)->change();
        });
    }
};
