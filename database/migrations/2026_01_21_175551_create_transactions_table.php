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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Siapa kasirnya
            $table->string('invoice_code')->unique();    // INV-202511-001 (String function nanti main disini)
            $table->dateTime('transaction_date');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0); // 10% atau 11%
            $table->decimal('service_charge', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('cash_paid', 15, 2);       // Uang yang dikasih
            $table->decimal('change_returned', 15, 2); // Kembalian
            $table->enum('payment_method', ['cash', 'qris', 'transfer']);
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
