<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessLocationsWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_locations_working_hours', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id');
            $table->integer('location_id');
            $table->string('week_day')->nullable();
            $table->time('opens_at')->nullable();
            $table->time('close_at')->nullable();
            $table->integer('status')->default(1)->comment('0 = closed | 1 = open');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_locations_working_hours');
    }
}
