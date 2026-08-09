<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserPasswordSeeder extends Seeder
{

    public function run()
    {

        User::query()->update([

            'password' => Hash::make('password'),

        ]);

    }

}
