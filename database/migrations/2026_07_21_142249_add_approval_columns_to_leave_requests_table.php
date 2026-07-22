<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('leave_requests', 'approved_by')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->foreignId('approved_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('leave_requests', 'approved_at')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('leave_requests', 'approved_by')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            });
        }

        if (Schema::hasColumn('leave_requests', 'approved_at')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->dropColumn('approved_at');
            });
        }
    }
};
