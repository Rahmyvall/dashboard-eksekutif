<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_performance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performance_indicator_id')->constrained()->restrictOnDelete();
            $table->decimal('target_value', 15, 2)->default(0);
            $table->decimal('actual_value', 15, 2)->default(0);
            $table->decimal('score', 5, 2)->default(0);
            $table->decimal('weight', 5, 2)->default(0);
            $table->decimal('weighted_score', 7, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['employee_performance_id', 'performance_indicator_id'], 'performance_detail_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_details');
    }
};
