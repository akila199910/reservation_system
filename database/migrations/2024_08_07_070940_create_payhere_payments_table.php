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
        Schema::create('payhere_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('reservation_id')->index('reservation_id');
            $table->string('payment_id')->nullable();
            $table->string('payhere_amount')->nullable();
            $table->string('payhere_currency')->nullable();
            $table->string('status_code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payhere_payments');
    }
};
