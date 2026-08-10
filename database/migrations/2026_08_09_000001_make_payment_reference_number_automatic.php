<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('payments')
            ->orderBy('id')
            ->eachById(function (object $payment): void {
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'reference_number' => sprintf(
                            'REF-%s-%06d',
                            date('Ymd', strtotime((string) $payment->payment_date)),
                            $payment->id
                        ),
                    ]);
            });

        Schema::table('payments', function (Blueprint $table): void {
            $table->unique('reference_number', 'payments_reference_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table): void {
            $table->dropUnique('payments_reference_number_unique');
        });
    }
};
