<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderApiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $orders = Order::with('user', 'orderItems.menu')->get();
        } else {
            $orders = Order::with('orderItems.menu')
                ->where('user_id', $user->id)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Data order berhasil diambil'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1|max:20',
            'customer_notes' => 'nullable|string|max:500'
        ]);

        // Generate order number
        $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        
        // Get latest queue number for today
        $latestQueue = Order::whereDate('created_at', today())->max('queue_number');
        $queueNumber = $latestQueue ? $latestQueue + 1 : 1;

        // Calculate total and prepare order items
        $totalAmount = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            $subtotal = $menu->price * $item['quantity'];
            $totalAmount += $subtotal;

            $orderItems[] = [
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'unit_price' => $menu->price
            ];
        }

        // Create order
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'queue_number' => $queueNumber,
            'customer_notes' => $request->customer_notes
        ]);

        // Create order items
        foreach ($orderItems as $orderItem) {
            $order->orderItems()->create($orderItem);
        }

        // Load relationships for response
        $order->load('orderItems.menu');

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Order berhasil dibuat. Nomor Antrian: ' . $queueNumber
        ], 201);
    }

    public function show(Order $order)
    {
        // Authorization check
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $order->load('orderItems.menu', 'user');

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Data order berhasil diambil'
        ]);
    }

    public function update(Request $request, Order $order)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat mengupdate order'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send notification to customer when status changes
        if ($request->status !== $oldStatus) {
            Notification::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'title' => 'Status Pesanan Diubah',
                'message' => "Pesanan #{$order->order_number} status diubah menjadi {$request->status}"
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Status order berhasil diupdate'
        ]);
    }

    public function destroy(Order $order)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat menghapus order'
            ], 403);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dihapus'
        ], 204);
    }

    public function userOrders()
    {
        $orders = Order::with('orderItems.menu')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Data order user berhasil diambil'
        ]);
    }
}