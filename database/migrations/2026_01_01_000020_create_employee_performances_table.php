<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performance_period_id')->constrained()->cascadeOnDelete();
            $table->decimal('attendance_score', 5, 2)->default(0);
            $table->decimal('target_score', 5, 2)->default(0);
            $table->decimal('quality_score', 5, 2)->default(0);
            $table->decimal('timeliness_score', 5, 2)->default(0);
            $table->decimal('customer_score', 5, 2)->default(0);
            $table->decimal('final_score', 5, 2)->default(0);
            $table->string('grade', 10)->nullable();
            $table->unsignedInteger('ranking')->nullable();
            $table->foreignId('evaluator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('evaluation_notes')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'performance_period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_performances');
    }
};
