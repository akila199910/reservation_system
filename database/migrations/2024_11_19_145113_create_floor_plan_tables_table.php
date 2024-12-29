<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorPlanTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floor_plan_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('plan_id')->index('plan_id');
            $table->integer('table_id')->index('table_id');
            $table->decimal('table_width',20,8)->default(0)->comment('Pixel Based');
            $table->decimal('table_height',20,8)->default(0)->comment('Pixel Based');
            $table->decimal('table_pos_x',20,8)->default(0)->comment('Pixel Based');
            $table->decimal('table_pos_y',20,8)->default(0)->comment('Pixel Based');
            $table->integer('created_by')->index('created_by');
            $table->integer('updated_by')->index('updated_by');
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
        Schema::dropIfExists('floor_plan_tables');
    }
}
