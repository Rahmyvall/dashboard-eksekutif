<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number', 50)->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('service_order_id')->nullable()->constrained()->nullOnDelete();
            $table->date('complaint_date');
            $table->string('subject', 180);
            $table->text('description');
            $table->string('priority', 30)->default('medium')->index();
            $table->string('status', 30)->default('open')->index();
            $table->foreignId('assigned_to')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_complaints');
    }
};
