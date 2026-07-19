<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('position_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('employee_number', 50)->unique();
            $table->string('full_name', 150);
            $table->string('gender', 20)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable()->index();
            $table->text('address')->nullable();
            $table->date('hire_date');
            $table->string('employment_status', 50)->default('permanent')->index();
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->string('photo_path')->nullable();
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
