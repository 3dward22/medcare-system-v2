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
        $table->dateTime('completed_datetime')->nullable()->after('approved_datetime');
        $table->string('temperature')->nullable()->after('completed_datetime');
        $table->string('blood_pressure')->nullable()->after('temperature');
        $table->string('heart_rate')->nullable()->after('blood_pressure');
        $table->text('additional_notes')->nullable()->after('heart_rate');
    });
}

public function down(): void
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn([
            'completed_datetime',
            'temperature',
            'blood_pressure',
            'heart_rate',
            'additional_notes',
        ]);
    });
}

};
