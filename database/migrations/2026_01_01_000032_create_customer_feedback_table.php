<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('service_quality_score')->default(0);
            $table->unsignedTinyInteger('employee_service_score')->default(0);
            $table->unsignedTinyInteger('timeliness_score')->default(0);
            $table->unsignedTinyInteger('price_score')->default(0);
            $table->decimal('overall_score', 4, 2)->default(0);
            $table->text('comments')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['customer_id', 'service_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_feedback');
    }
};
