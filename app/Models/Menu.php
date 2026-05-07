<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedPrice()
    {
        return 'Rp ' . number_format($this->price ?: 0, 0, ',', '.');
    }

    public function scopeAvailable($query)
    {
        if (Schema::hasColumn('menus', 'is_available')) {
            return $query->where('is_available', true);
        }
        return $query;
    }

    public function scopeActive($query)
    {
        return $query->where('is_available', true);
    }
}