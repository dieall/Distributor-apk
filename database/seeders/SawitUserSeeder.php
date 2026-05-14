<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SawitUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Sawit
        User::firstOrCreate(
            ['email' => 'adminsawit@example.com'],
            [
                'name' => 'Admin Sawit',
                'password' => Hash::make('password'),
                'role' => 'adminsawit',
                'is_active' => true,
                'phone' => '081234567890',
            ]
        );

        // Accounting Sawit
        User::firstOrCreate(
            ['email' => 'accountingsawit@example.com'],
            [
                'name' => 'Accounting Sawit',
                'password' => Hash::make('password'),
                'role' => 'accountingsawit',
                'is_active' => true,
                'phone' => '081234567891',
            ]
        );

        // Direktur Sawit
        User::firstOrCreate(
            ['email' => 'direktursawit@example.com'],
            [
                'name' => 'Direktur Sawit',
                'password' => Hash::make('password'),
                'role' => 'direktursawit',
                'is_active' => true,
                'phone' => '081234567892',
            ]
        );

        $this->command->info('✓ User sawit berhasil dibuat!');
        $this->command->info('  - adminsawit@example.com / password');
        $this->command->info('  - accountingsawit@example.com / password');
        $this->command->info('  - direktursawit@example.com / password');
    }
}
