<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergency_records', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete(); // student (user with role=student)
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete(); // nurse user

            // Timestamps
            $table->timestamp('reported_at');

            // Vitals
            $table->string('temperature', 50)->nullable();
            $table->string('blood_pressure', 50)->nullable();
            $table->string('heart_rate', 50)->nullable();

            // Text fields
            $table->text('symptoms');
            $table->text('diagnosis');
            $table->text('treatment');
            $table->text('additional_notes')->nullable();

            // Guardian
            $table->boolean('guardian_notified')->default(false);
            $table->timestamp('guardian_notified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_records');
    }
};
