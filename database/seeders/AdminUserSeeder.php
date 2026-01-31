<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@admin.com');

        if (!User::where('email', $email)->exists()) {
            User::create([
                'name' => env('ADMIN_NAME', 'Administrator'),
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'remember_token' => Str::random(10),
                'role' => env('ADMIN_ROLE', 'superadmin'),
            ]);
        }
    }
}
