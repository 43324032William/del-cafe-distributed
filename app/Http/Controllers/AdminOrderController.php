<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin-only');
        
        $status = $request->input('status');
        
        $orders = Order::with('user', 'orderItems.menu')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function edit(Order $order)
    {
        Gate::authorize('admin-only');
        
        $order->load('orderItems.menu', 'user');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        Gate::authorize('admin-only');
        
        $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send notification to customer when status changes
        if ($request->status !== $oldStatus) {
            $statusMessages = [
                'processing' => 'sedang diproses',
                'ready' => 'sudah siap untuk diambil',
                'completed' => 'telah selesai',
                'cancelled' => 'telah dibatalkan'
            ];

            if (array_key_exists($request->status, $statusMessages)) {
                Notification::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'title' => 'Status Pesanan Diubah',
                    'message' => "Pesanan #{$order->order_number} {$statusMessages[$request->status]}. Nomor Antrian: {$order->queue_number}"
                ]);
            }
        }

        return redirect()->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diupdate');
    }

    public function financialReport(Request $request)
    {
        Gate::authorize('admin-only');
        
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        $orders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with('orderItems.menu')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('total_amount');
            
        $totalOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();
            
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Daily revenue data for chart
        $dailyRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.financial-report', compact(
            'orders', 
            'totalRevenue', 
            'totalOrders', 
            'averageOrderValue',
            'startDate',
            'endDate',
            'dailyRevenue'
        ));
    }

    public function destroy(Order $order)
    {
        Gate::authorize('admin-only');
        
        $order->delete();
        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dihapus!');
    }
}