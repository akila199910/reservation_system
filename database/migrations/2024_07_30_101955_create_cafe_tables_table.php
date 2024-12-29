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
        Schema::create('cafe_tables', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->string('name');
            $table->integer('perference_id');
            $table->integer('capacity');
            $table->integer('amount');
            $table->string('image')->nullable();
            $table->integer('status');
            $table->integer('reservation_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cafe_tables');
    }
};
