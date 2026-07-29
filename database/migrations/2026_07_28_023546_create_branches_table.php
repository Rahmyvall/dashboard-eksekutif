<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();

            $table->string('branch_code', 50)->unique();
            $table->string('branch_name', 100);
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email', 100)->unique();

            /*
             * Kepala cabang diambil dari tabel users.
             * Jika user kepala cabang dihapus, manager_id menjadi NULL.
             */
            $table->foreignId('manager_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            /*
             * Status:
             * 1 = Aktif
             * 0 = Nonaktif
             */
            $table->smallInteger('status')->default(1)->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index('branch_name');
            $table->index('deleted_at');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
