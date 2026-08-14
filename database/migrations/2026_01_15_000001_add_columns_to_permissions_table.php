<?php

declare (strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add columns to permissions table if they don't exist
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'display_name')) {
                $table->string('display_name')->nullable()->after('name');
            }

            if (!Schema::hasColumn('permissions', 'description')) {
                $table->text('description')->nullable()->after('display_name');
            }

            if (!Schema::hasColumn('permissions', 'category')) {
                $table->string('category')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'display_name')) {
                $table->dropColumn('display_name');
            }

            if (Schema::hasColumn('permissions', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('permissions', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
