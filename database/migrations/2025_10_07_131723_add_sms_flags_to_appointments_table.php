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
    Schema::table('appointments', function (Blueprint $table) {
        $table->boolean('student_sms_sent')->default(false);
        $table->boolean('guardian_sms_sent')->default(false);
        $table->text('findings')->nullable(); // nurse notes / diagnosis
});

            //
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
