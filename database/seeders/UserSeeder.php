<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Administrator',
                'email'     => 'admin@distributor.com',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'is_active' => true,
                'phone'     => '081234567890',
            ]
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info('Users berhasil dibuat:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@distributor.com', 'password']
            ]
        );
    }
}
