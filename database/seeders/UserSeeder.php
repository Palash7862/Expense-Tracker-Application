<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'Test '.Str::random(10),
            'email'             => 'test@example.com ',
            'password'          => Hash::make('password123'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        User::factory(3)->create([
            'name' => fn () => 'Test '.Str::random(10),
            'password' => Hash::make('password123'),
        ]);
    }
}
