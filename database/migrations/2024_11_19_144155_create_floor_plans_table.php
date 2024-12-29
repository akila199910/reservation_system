<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floor_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('business_id')->index('business_id');
            $table->string('ref_no')->nullable();
            $table->integer('section_id')->index('section_id');
            $table->decimal('floor_width',20,2)->default(0);
            $table->decimal('floor_length',20,2)->default(0);
            $table->integer('status')->default(0)->comment('0 = Inactive 1 = Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('floor_plans');
    }
}
