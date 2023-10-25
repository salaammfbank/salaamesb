<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('email', 'mnoor@salaammfbank.co.ke')->exists()) {
            User::factory()->create([
                'name' => 'Noor Abdi',
                'email' => 'mnoor@salaammfbank.co.ke',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]);
        }
    }
}
