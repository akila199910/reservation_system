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
        Schema::create('reservation_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('reservation_id')->index('reservation_id');
            $table->integer('no_stars')->default(0);
            $table->longText('message')->nullable();
            $table->integer('status')->default(0)->comment('0 = Pending | 1 = Approved | 2 = Rejected');
            $table->longText('rejected_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_reviews');
    }
};
