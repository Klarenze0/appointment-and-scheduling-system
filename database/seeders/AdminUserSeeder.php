<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@appointments.com'],
            [
                'name'     => 'System Administrator',
                'password' => 'Admin@12345',
                'role'     => 'admin',
                'phone'    => null,
            ]
        );

        $this->command->info('Admin user seeded: admin@appointments.com / Admin@12345');
    }
}