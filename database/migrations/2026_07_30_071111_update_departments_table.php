<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Buat tabel jika belum ada
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique();
                $table->string('name', 150);
                $table->text('description')->nullable();
                $table->string('status', 30)
                    ->default('active')
                    ->index();
                $table->timestamps();
                $table->softDeletes();
            });

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Ubah kolom yang sudah ada
        |--------------------------------------------------------------------------
        */
        Schema::table('departments', function (Blueprint $table) {
            $table->string('code', 30)->change();
            $table->string('name', 150)->change();
            $table->text('description')->nullable()->change();
            $table->string('status', 30)
                ->default('active')
                ->change();
        });

        /*
        |--------------------------------------------------------------------------
        | Tambahkan deleted_at jika belum ada
        |--------------------------------------------------------------------------
        */
        if (! Schema::hasColumn('departments', 'deleted_at')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('departments') &&
            Schema::hasColumn('departments', 'deleted_at')
        ) {
            Schema::table('departments', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
