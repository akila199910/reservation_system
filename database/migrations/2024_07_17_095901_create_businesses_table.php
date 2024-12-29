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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact');
            $table->string('address');
            $table->integer('status')->default(1); // 0: Inactive, 1: Active
            $table->string('snap_auth_key')->nullable();
            $table->integer('ibson_business')->default(1); // 0: No, 1: Yes
            $table->string('ibson_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
