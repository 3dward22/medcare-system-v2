<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Link to users table (student)
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            // Date & time the student requested
            $table->dateTime('requested_datetime');

            // Approved date & time (nurse/admin can change)
            $table->dateTime('approved_datetime')->nullable();

            // Status options
            $table->enum('status', ['pending', 'approved', 'rescheduled', 'declined', 'completed', 'cancelled'])
            ->default('pending');


            // Which user approved it (nurse/admin)
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            // Optional note from nurse/admin
            $table->text('admin_note')->nullable();

            $table->timestamps();

            // Indexes for faster queries
            $table->index('requested_datetime');
            $table->index('approved_datetime');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
