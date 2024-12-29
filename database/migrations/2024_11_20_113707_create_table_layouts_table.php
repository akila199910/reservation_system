<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->nullable();
            $table->string('layout_name')->index('layout_name');
            $table->integer('type_id')->index('type_id');
            $table->string('normal_image')->nullable();
            $table->string('checkedin_image')->nullable();
            $table->integer('status')->default(1)->comment('0 = Inactive 1 Active');
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
        Schema::dropIfExists('table_layouts');
    }
}
