<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'queue_number',
        'customer_name',
        'customer_phone', 
        'customer_email',
        'notes',
        'total_amount',
        'status',
        'user_id'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}