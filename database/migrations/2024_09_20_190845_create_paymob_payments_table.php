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
        Schema::create('paymob_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('method_id');
            $table->float('amount');
            $table->string('reference_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('type')->nullable();
            $table->text('payment_url')->nullable();
            $table->enum('payment_status',['pending','paid','failed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymob_payments');
    }
};
