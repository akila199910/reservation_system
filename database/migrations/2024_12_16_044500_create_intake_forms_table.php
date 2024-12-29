<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntakeFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intake_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('ref_no')->nullable();
            $table->string('f_name');
            $table->string('l_name');
            $table->date('dob');
            $table->string('gender')->default('M')->comment('M = Male | F = Female | O = Other');
            $table->string('email')->nullable();
            $table->string('contact');
            $table->string('address');
            $table->string('reason')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('description')->nullable();
            $table->string('communication_mode')->default(1)->comment('1 = Email | 2 = Phone | 3 = SMS | 4 = Physical');
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
        Schema::dropIfExists('intake_forms');
    }
}
