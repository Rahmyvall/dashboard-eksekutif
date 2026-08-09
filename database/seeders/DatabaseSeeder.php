<?php
namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

User::whereHas('roles', function ($query) {

    $query->whereIn('name', [
        'direktur_utama',
        'hrd_manager',
        'manager_departemen',
        'admin_pelayanan',
        'admin_operasional',
        'finance_staff',
        'auditor_internal',
        'karyawan',
    ]);

})
    ->each(function ($user) {

        $user->update([
            'password' => Hash::make('password'),
        ]);

    });

echo "Password semua role berhasil diperbarui";

    }
}
