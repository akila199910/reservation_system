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
        Schema::table('notification_settings', function (Blueprint $table) {
            $table->boolean('rejected_mail')->default(0)->after('business_id'); // 0 = Inactive, 1 = Active
            $table->boolean('rejected_text')->default(0)->after('rejected_mail'); // 0 = Inactive, 1 = Active
            $table->boolean('completed_mail')->default(0)->after('cancel_text'); // 0 = Inactive, 1 = Active
            $table->boolean('completed_text')->default(0)->after('completed_mail'); // 0 = Inactive, 1 = Active
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_settings', function (Blueprint $table) {
            //
        });
    }
};
