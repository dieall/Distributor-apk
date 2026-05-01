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

    public function isGudang(): bool
    {
        return $this->role === 'gudang';
    }

    public function isSales(): bool
    {
        return $this->role === 'sales';
    }

    public function isSupplier(): bool
    {
        return $this->role === 'supplier';
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'     => 'Administrator',
            'gudang'    => 'Gudang',
            'sales'     => 'Sales',
            'supplier'  => 'Supplier',
            'pelanggan' => 'Pelanggan',
            default     => ucfirst($this->role),
        };
    }

    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin'     => 'admin.dashboard',
            'gudang'    => 'gudang.dashboard',
            'sales'     => 'sales.dashboard',
            'supplier'  => 'supplier.dashboard',
            'pelanggan' => 'pelanggan.dashboard',
            default     => 'login',
        };
    }
}
