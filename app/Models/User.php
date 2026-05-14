<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'address',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDirektur(): bool
    {
        return $this->role === 'direktur';
    }

    public function isGudang(): bool
    {
        return $this->role === 'gudang';
    }

    public function isSales(): bool
    {
        return $this->role === 'sales';
    }

    public function isPurchasing(): bool
    {
        return $this->role === 'purchasing';
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    // Role Sawit Methods
    public function isAdminSawit(): bool
    {
        return $this->role === 'adminsawit';
    }

    public function isAccountingSawit(): bool
    {
        return $this->role === 'accountingsawit';
    }

    public function isDirekturSawit(): bool
    {
        return $this->role === 'direktursawit';
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'            => 'Administrator',
            'direktur'         => 'Direktur',
            'gudang'           => 'Gudang',
            'sales'            => 'Sales',
            'purchasing'       => 'Purchasing',
            'pelanggan'        => 'Pelanggan',
            'adminsawit'       => 'Admin Sawit',
            'accountingsawit'  => 'Accounting Sawit',
            'direktursawit'    => 'Direktur Sawit',
            default            => ucfirst($this->role),
        };
    }

    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin'            => 'admin.dashboard',
            'direktur'         => 'admin.dashboard',
            'gudang'           => 'gudang.dashboard',
            'sales'            => 'sales.dashboard',
            'purchasing'       => 'purchasing.dashboard',
            'pelanggan'        => 'pelanggan.dashboard',
            'adminsawit'       => 'sawit.admin.dashboard',
            'accountingsawit'  => 'sawit.accounting.dashboard',
            'direktursawit'    => 'sawit.direktur.dashboard',
            default            => 'login',
        };
    }
}
