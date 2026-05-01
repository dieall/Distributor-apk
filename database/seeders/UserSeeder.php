<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrator',
                'email'    => 'admin@distributor.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'is_active' => true,
                'phone'    => '081234567890',
            ],
            [
                'name'     => 'Petugas Gudang',
                'email'    => 'gudang@distributor.com',
                'password' => Hash::make('password'),
                'role'     => 'gudang',
                'is_active' => true,
                'phone'    => '081234567891',
            ],
            [
                'name'     => 'Sales Manager',
                'email'    => 'sales@distributor.com',
                'password' => Hash::make('password'),
                'role'     => 'sales',
                'is_active' => true,
                'phone'    => '081234567892',
            ],
            [
                'name'     => 'PT. Supplier Jaya',
                'email'    => 'supplier@distributor.com',
                'password' => Hash::make('password'),
                'role'     => 'supplier',
                'is_active' => true,
                'phone'    => '081234567893',
            ],
            [
                'name'     => 'Toko Pelanggan',
                'email'    => 'pelanggan@distributor.com',
                'password' => Hash::make('password'),
                'role'     => 'pelanggan',
                'is_active' => true,
                'phone'    => '081234567894',
            ],
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
                ['Admin',     'admin@distributor.com',     'password'],
                ['Gudang',    'gudang@distributor.com',    'password'],
                ['Sales',     'sales@distributor.com',     'password'],
                ['Supplier',  'supplier@distributor.com',  'password'],
                ['Pelanggan', 'pelanggan@distributor.com', 'password'],
            ]
        );
    }
}
