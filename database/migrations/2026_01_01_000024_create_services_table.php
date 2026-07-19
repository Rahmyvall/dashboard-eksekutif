<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->constrained()->restrictOnDelete();
            $table->string('service_code', 50)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('base_price', 15, 2)->default(0);
            $table->unsignedInteger('estimated_duration_minutes')->nullable();
            $table->string('unit', 50)->default('service');
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
