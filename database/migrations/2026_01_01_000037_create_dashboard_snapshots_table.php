<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('snapshot_date')->unique();
            $table->unsignedInteger('total_employees')->default(0);
            $table->unsignedInteger('active_employees')->default(0);
            $table->decimal('average_productivity', 5, 2)->default(0);
            $table->unsignedInteger('total_transactions')->default(0);
            $table->decimal('total_revenue', 18, 2)->default(0);
            $table->decimal('total_receivables', 18, 2)->default(0);
            $table->unsignedInteger('completed_orders')->default(0);
            $table->unsignedInteger('cancelled_orders')->default(0);
            $table->decimal('average_customer_score', 4, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_snapshots');
    }
};
