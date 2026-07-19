<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50)->unique();
            $table->string('customer_type', 30)->default('individual')->index();
            $table->string('name', 150);
            $table->string('company_name', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
