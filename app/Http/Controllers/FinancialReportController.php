<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // Get filter parameters
        $period = $request->get('period', 'month');
        $status = $request->get('status', 'all');
        $customStart = $request->get('start_date');
        $customEnd = $request->get('end_date');

        // Set date range based on period
        $dateRange = $this->getDateRange($period, $customStart, $customEnd);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Query data pesanan dengan filter
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        
        if ($status != 'all') {
            $ordersQuery->where('status', $status);
        }
        
        $orders = $ordersQuery->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $totalRevenue = $orders->where('status', 'completed')->sum('total_amount');
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $pendingOrders = $orders->where('status', 'pending')->count();
        $processingOrders = $orders->where('status', 'processing')->count();
        $cancelledOrders = $orders->where('status', 'cancelled')->count();

        // Popular menu items
        $popularMenus = DB::table('order_items')
                        ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
                        ->where('orders.status', 'completed')
                        ->selectRaw('menus.name, 
                                   SUM(order_items.quantity) as total_sold, 
                                   SUM(order_items.quantity * order_items.price) as revenue')
                        ->groupBy('menus.id', 'menus.name')
                        ->orderBy('total_sold', 'desc')
                        ->take(10)
                        ->get();

        // Data untuk chart (jika diperlukan)
        $revenueByDay = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                            ->where('status', 'completed')
                            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get();

        return view('admin.financial-report', compact(
            'startDate',
            'endDate',
            'period',
            'status',
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'processingOrders',
            'cancelledOrders',
            'popularMenus',
            'revenueByDay',
            'orders'
        ));
    }

    private function getDateRange($period, $customStart = null, $customEnd = null)
    {
        $now = Carbon::now();

        switch ($period) {
            case 'today':
                return [
                    'start' => $now->format('Y-m-d'),
                    'end' => $now->format('Y-m-d')
                ];

            case 'week':
                return [
                    'start' => $now->startOfWeek()->format('Y-m-d'),
                    'end' => $now->endOfWeek()->format('Y-m-d')
                ];

            case 'month':
                return [
                    'start' => $now->startOfMonth()->format('Y-m-d'),
                    'end' => $now->endOfMonth()->format('Y-m-d')
                ];

            case 'year':
                return [
                    'start' => $now->startOfYear()->format('Y-m-d'),
                    'end' => $now->endOfYear()->format('Y-m-d')
                ];

            case 'custom':
                return [
                    'start' => $customStart ?: $now->startOfMonth()->format('Y-m-d'),
                    'end' => $customEnd ?: $now->endOfMonth()->format('Y-m-d')
                ];

            default:
                return [
                    'start' => $now->startOfMonth()->format('Y-m-d'),
                    'end' => $now->endOfMonth()->format('Y-m-d')
                ];
        }
    }
}