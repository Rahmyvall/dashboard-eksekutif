<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performance_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performance_indicator_id')->constrained()->restrictOnDelete();
            $table->decimal('target_value', 15, 2)->default(0);
            $table->decimal('actual_value', 15, 2)->default(0);
            $table->decimal('achievement_percentage', 7, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['employee_id', 'performance_period_id', 'performance_indicator_id'], 'employee_target_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_targets');
    }
};
