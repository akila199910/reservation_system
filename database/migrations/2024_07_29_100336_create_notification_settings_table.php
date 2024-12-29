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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->Integer('business_id');
            $table->boolean('confirmation_mail')->default(0); // 0 = Inactive, 1 = Active
            $table->boolean('confirmation_text')->default(0); // 0 = Inactive, 1 = Active
            $table->boolean('reminder_mail')->default(0); // 0 = Inactive, 1 = Active
            $table->boolean('reminder_text')->default(0); // 0 = Inactive, 1 = Active
            $table->boolean('cancel_mail')->default(0); // 0 = Inactive, 1 = Active
            $table->boolean('cancel_text')->default(0); // 0 = Inactive, 1 = Active
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
