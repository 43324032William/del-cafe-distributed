<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone', // TAMBAHKAN INI
        'role'
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
        ];
    }

    // Relationships
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Helper methods dengan fallback
    public function isAdmin(): bool
    {
        // Fallback jika kolom role belum ada
        if (!isset($this->role)) {
            return false;
        }
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        // Fallback jika kolom role belum ada
        if (!isset($this->role)) {
            return true; // Default ke customer
        }
        return $this->role === 'customer';
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    // Accessor untuk phone dengan fallback
    public function getPhoneAttribute($value)
    {
        return $value ?? 'Tidak ada nomor telepon';
    }

    // Method untuk mendapatkan data user yang aman untuk order
    public function getOrderCustomerData(): array
    {
        return [
            'customer_name' => $this->name,
            'customer_phone' => $this->phone,
            'customer_email' => $this->email,
        ];
    }
}