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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->index('ref_no')->nullable();
            $table->integer('business_id')->index('business_id')->nullable();
            $table->integer('client_id')->index('client_id')->nullable();
            $table->integer('cafetable_id')->index('cafetable_id')->nullable();
            $table->date('request_date')->index('request_date')->nullable();
            $table->dateTime('request_start_time')->nullable();
            $table->dateTime('request_end_time')->nullable();
            $table->integer('no_of_people')->default(1);
            $table->integer('extra_people')->default(0);
            $table->decimal('amount',20,2)->default(0);
            $table->decimal('discount',20,2)->default(0);
            $table->decimal('extra_amount',20,2)->default(0);
            $table->decimal('service_amount',20,2)->default(0);
            $table->decimal('final_amount',20,2)->default(0);
            $table->integer('status')->default(0)->comment('0 = Pending, 1 = Rejected, 2 = Confirmed, 3 = Canceled, 4 = Completed');
            $table->integer('payment_type')->default(0)->comment('0 = Direct Pay, 1 = Online Pay');
            $table->integer('paid_status')->default(0)->comment('0 = Not paid, 1 = Paid');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
