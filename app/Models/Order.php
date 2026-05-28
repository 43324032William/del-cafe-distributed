<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email', 
        'status',
        'total_amount',
        'queue_number',
        'notes' // <--- GANTI INI WIL! Harus 'notes' sesuai nama kolom di phpMyAdmin lu
    ];

    // Relasi ke Item Order (Sudah benar)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke User (Sudah benar)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
 