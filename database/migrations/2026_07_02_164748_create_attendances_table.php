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
        Schema::create('attendances', function (Blueprint $table) {

            $table->id();

            // Employee
            $table->string('employee_id');

            // One attendance per day
            $table->date('date');

            // Full Day / Half Day
            $table->enum('attendance_type', [
                'FULL_DAY',
                'HALF_DAY'
            ])->default('FULL_DAY');

            // Current attendance state
            $table->enum('attendance_state', [
                'CHECKED_IN',
                'CHECKED_OUT'
            ])->default('CHECKED_IN');

            // Final attendance status
            $table->enum('status', [
                'PRESENT',
                'ABSENT'
            ])->default('PRESENT');

            // Timings
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();

            // Work calculation
            $table->integer('worked_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);

            $table->timestamps();

            // Only one attendance per employee per day
            $table->unique(['employee_id', 'date']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};