<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function adminDashboard()
    {
        // Debug info
        Log::info('Accessing admin dashboard:', [
            'user_id' => Auth::id(),
            'email' => Auth::user()->email,
            'role' => Auth::user()->role ?? 'null',
            'ip' => request()->ip()
        ]);

        // Cek role manual
        if (Auth::user()->role !== 'admin') {
            Log::warning('Non-admin user tried to access admin dashboard:', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email
            ]);
            return redirect('/admin/login')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // Ambil data untuk dashboard
        $totalOrders = DB::table('orders')->count();
        $pendingOrders = DB::table('orders')->where('status', 'pending')->count();
        $completedOrders = DB::table('orders')->where('status', 'completed')->count();
        $totalRevenue = DB::table('orders')->where('status', 'completed')->sum('total_amount');
        $totalMenus = DB::table('menus')->count();

        // Ambil orders terbaru
        $recentOrders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        Log::info('Dashboard data loaded successfully');

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'totalMenus',
            'recentOrders'
        ));
    }
}