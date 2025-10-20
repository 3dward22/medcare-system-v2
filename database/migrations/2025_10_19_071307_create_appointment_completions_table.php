<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('appointment_completions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
        $table->dateTime('completed_datetime');
        $table->string('temperature')->nullable();
        $table->string('blood_pressure')->nullable();
        $table->string('heart_rate')->nullable();
        $table->text('findings')->nullable();
        $table->text('additional_notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_completions');
    }
};
