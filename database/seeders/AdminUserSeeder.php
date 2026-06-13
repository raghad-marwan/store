<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@techstore.com'],
            [
                'name' => 'مدير المتجر',
                'email' => 'admin@techstore.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin user created: admin@techstore.com / password123');
    }
}
