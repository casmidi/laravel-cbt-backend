<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create(); //dynamic
        \App\Models\User::create([              //Static
            'name' => 'Admin Casmidi',
            'email' => 'casmidi@fic10.com',
            'password' => Hash::make('12345678'),
            'roles'  => 'ADMIN',
            'phone' =>'08128621234',
        ]);

    }
}
