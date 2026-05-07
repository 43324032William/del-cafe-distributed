<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Jobs\SendNotificationJob;

class CheckoutController extends Controller
{
    public function show() { return view('checkout'); }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_address' => 'required|string',
            'cart' => 'required|json'
        ]);

        $cart = json_decode($request->cart, true);
        $total = 0;
        foreach ($cart as $item) { $total += $item['price'] * $item['quantity']; }

        $order = Order::create([
            'guest_name' => $request->guest_name,
            'guest_phone' => $request->guest_phone,
            'guest_address' => $request->guest_address,
            'notes' => $request->notes,
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // Jalankan Job untuk dikirim ke RabbitMQ
        SendNotificationJob::dispatch($order);

        return redirect()->route('checkout.success')->with('order_id', $order->id);
    }

    public function success() { return view('checkout-success'); }
}