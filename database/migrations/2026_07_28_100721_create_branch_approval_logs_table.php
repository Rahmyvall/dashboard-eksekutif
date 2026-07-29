<?php

declare (strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel histori approval cabang.
     */
    public function up(): void
    {
        Schema::create('branch_approval_logs', function (Blueprint $table): void {
            $table->id();

            /*
             * Cabang yang diproses.
             */
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            /*
             * User yang melakukan tindakan.
             */
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
             * Role user saat melakukan tindakan.
             */
            $table->string('role_name', 100)
                ->nullable();

            /*
             * Contoh:
             * submitted, approved, rejected.
             */
            $table->string('action', 30);

            /*
             * Status sebelum dan setelah tindakan.
             */
            $table->string('from_status', 20)
                ->nullable();

            $table->string('to_status', 20)
                ->nullable();

            /*
             * Role berikutnya yang harus memproses.
             */
            $table->string('next_approval_role', 100)
                ->nullable();

            /*
             * Catatan approval atau alasan penolakan.
             */
            $table->text('note')
                ->nullable();

            $table->timestamps();

            $table->index(
                ['branch_id', 'created_at'],
                'branch_approval_logs_branch_created_index'
            );

            $table->index(
                ['action', 'created_at'],
                'branch_approval_logs_action_created_index'
            );
        });
    }

    /**
     * Menghapus tabel histori approval.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_approval_logs');
    }
};
