<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * HUBUNGKAN KE POSTGRESQL
     * Ini akan memberitahu Laravel untuk mencari tabel 'notifications' 
     * di koneksi pgsql (PostgreSQL) yang sudah kita buat di .env tadi.
     */
    protected $connection = 'pgsql';

    protected $fillable = [
        'user_id',
        'order_id',
        'title',
        'message',
        'is_read'
    ];

    /**
     * CATATAN RELASI:
     * Karena User dan Order ada di MySQL, sedangkan Notification ada di PostgreSQL,
     * relasi standard Eloquent mungkin akan sedikit lebih lambat (Cross-Database Join).
     * Namun untuk pengambilan data sederhana, kode di bawah ini tetap bisa digunakan.
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}