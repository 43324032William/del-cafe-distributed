<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->customerDashboard();
    }

    protected function adminDashboard()
    {
        $today = now()->format('Y-m-d');
        
        $stats = [
            'total_orders_today' => Order::whereDate('created_at', $today)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue_today' => Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total_amount'),
            'total_menus' => Menu::count(),
        ];

        // Recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Popular menus
        $popularMenus = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_ordered'))
            ->groupBy('menus.id', 'menus.name')
            ->orderBy('total_ordered', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'popularMenus'));
    }

    protected function customerDashboard()
    {
        $user = auth()->user();
        
        $recentOrders = Order::where('user_id', $user->id)
            ->with('orderItems.menu')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadNotifications = $user->unread_notifications_count;

        return view('customer.dashboard', compact('recentOrders', 'unreadNotifications'));
    }
}