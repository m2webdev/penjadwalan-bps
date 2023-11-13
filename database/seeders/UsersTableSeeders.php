<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'jk' => 'laki-laki',
            'role' => 'admin',
            'password' => Hash::make('12345'),
            'email_verified_at' => now(),
            'agama' => 'islam',
            'remember_token' => Str::random(10),
        ]);

        User::factory()->create([
            'name' => 'pengguna',
            'username' => 'pengguna',
            'jk' => 'laki-laki',
            'role' => 'pengguna',
            'agama' => 'islam',
            'password' => Hash::make('12345'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}