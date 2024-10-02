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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->unsignedBigInteger('user_id'); // Foreign key for user
            $table->string('transaction_id'); // Unique transaction ID
            $table->string('subscription_type'); // Type of subscription
            $table->string('payment_screenshot')->nullable(); // Path to payment screenshot (nullable)
            $table->timestamp('payment_date')->nullable(); // Payment date (nullable)
            $table->string('status')->default('pending'); // Status of the transaction
            $table->timestamps(); // Created at and updated at fields

            // Foreign key constraint (optional)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
