<?php

declare (strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom workflow approval pada tabel branches.
     */
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table): void {
            /*
             * Data cabang lama dianggap sudah disetujui.
             * Pengajuan baru akan diisi "pending" oleh controller.
             */
            $table->string('approval_status', 20)
                ->default('approved')
                ->index();

            /*
             * Jenis pengajuan:
             * create, update, atau delete.
             */
            $table->string('approval_action', 20)
                ->nullable();

            /*
             * Role yang sedang mendapat giliran persetujuan.
             */
            $table->string('pending_approval_role', 100)
                ->nullable()
                ->index();

            /*
             * Menyimpan data perubahan sementara.
             * Menggunakan JSONB karena database PostgreSQL.
             */
            $table->jsonb('pending_payload')
                ->nullable();

            /*
             * User yang mengajukan perubahan.
             */
            $table->foreignId('submitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
             * User terakhir yang memberikan persetujuan.
             */
            $table->foreignId('last_approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable();

            /*
             * User yang menolak pengajuan.
             */
            $table->foreignId('rejected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->text('rejection_note')
                ->nullable();
        });
    }

    /**
     * Menghapus kolom workflow approval.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table): void {
            /*
             * Foreign key harus dihapus sebelum kolomnya.
             */
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['last_approved_by']);
            $table->dropForeign(['rejected_by']);

            $table->dropIndex(['approval_status']);
            $table->dropIndex(['pending_approval_role']);

            $table->dropColumn([
                'approval_status',
                'approval_action',
                'pending_approval_role',
                'pending_payload',
                'submitted_by',
                'last_approved_by',
                'approved_at',
                'rejected_by',
                'rejected_at',
                'rejection_note',
            ]);
        });
    }
};
