<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $orderData;

    public function __construct($order)
    {
        $this->orderData = [
            'id' => $order->id,
            'customer_name' => $order->customer_name ?? $order->guest_name ?? 'Guest',
        ];
    }

    public function handle()
    {
        try {
            // Menggunakan koneksi mysql_notif yang merujuk ke del_cafe_replica
            DB::connection('mysql_notif')->table('notifications')->insert([
                'user_id'    => 1, 
                'order_id'   => $this->orderData['id'],
                'title'      => 'Pesanan Baru!',
                'message'    => 'Pesanan #' . $this->orderData['id'] . ' diterima dari ' . $this->orderData['customer_name'],
                'is_read'    => false,
                'type'       => 'order_created',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "\n [v] SUKSES: Data masuk ke database replika MySQL!\n";
            
        } catch (\Exception $e) {
            echo "\n [!] ERROR: " . $e->getMessage() . "\n";
            Log::error('Queue Error: ' . $e->getMessage());
            throw $e;
        }
    }
}