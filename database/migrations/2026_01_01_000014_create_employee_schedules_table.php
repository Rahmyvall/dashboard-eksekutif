<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_schedule_id')->constrained()->restrictOnDelete();
            $table->date('schedule_date');
            $table->timestamps();
            $table->unique(['employee_id', 'schedule_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_schedules');
    }
};
