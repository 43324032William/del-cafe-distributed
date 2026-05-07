<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Jobs\SendNotificationJob; // Pastikan ini ada!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends Controller
{
    // =================================================================
    // ADMIN FUNCTIONS (index, edit, update, printInvoice, destroy)
    // =================================================================

    private function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function index(Request $request)
    {
        if (!$this->isAdmin()) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $search = $request->get('search');
        $status = $request->get('status');

        $orders = Order::with(['orderItems.menu'])
                        ->when($search, function($query) use ($search) {
                            return $query->where(function($q) use ($search) {
                                $q->where('customer_name', 'like', '%'.$search.'%')
                                  ->orWhere('order_number', 'like', '%'.$search.'%')
                                  ->orWhere('customer_phone', 'like', '%'.$search.'%');
                            });
                        })
                        ->when($status, function($query) use ($status) {
                            return $query->where('status', $status);
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(10)
                        ->appends($request->except('page')); 

        $availableStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        
        return view('admin.orders.index', compact('orders', 'availableStatuses'));
    }

    public function edit(Order $order)
    {
        if (!$this->isAdmin()) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $order->load(['orderItems.menu']);
        $statuses = ['pending', 'processing', 'completed', 'cancelled']; 
        
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        if (!$this->isAdmin()) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses untuk aksi ini.');
        }

        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Status pesanan ' . $order->order_number . ' berhasil diupdate!');
    }

    public function printInvoice(Order $order)
    {
        if (!$this->isAdmin()) {
            return redirect('/')->with('error', 'Anda tidak memiliki izin untuk mencetak faktur.');
        }
        $order->load(['orderItems.menu']);
        return view('admin.orders.invoice-print', compact('order'));
    }

    public function destroy(Order $order)
    {
        if (!$this->isAdmin()) {
            return redirect('/')->with('error', 'Anda tidak memiliki izin untuk menghapus pesanan.');
        }

        try {
            DB::beginTransaction();
            $orderNumber = $order->order_number;
            $order->delete(); 
            DB::commit();

            return redirect()->route('admin.orders.index')
                             ->with('success', 'Pesanan ' . $orderNumber . ' berhasil dihapus.');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('💥 ERROR DELETING ORDER: ' . $e->getMessage());
            return redirect()->route('admin.orders.index')->with('error', 'Gagal menghapus pesanan.');
        }
    }

    // =================================================================
    // ORDERING FUNCTIONS (UTAMA)
    // =================================================================

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $customerData = [
            'customer_name'  => Auth::user()->name,
            'customer_phone' => Auth::user()->phone ?? '0',  
            'customer_email' => Auth::user()->email,
            'notes'          => $request->input('notes', ''),
            'user_id'        => Auth::id(),
        ];

        return $this->processOrder($request, $customerData);
    }
    
    public function storeGuestOrder(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        $customerData = [
            'customer_name'  => $request->input('customer_name'),
            'customer_phone' => $request->input('customer_phone'),
            'customer_email' => $request->input('customer_email', ''), 
            'notes'          => $request->input('notes', ''),
            'user_id'        => null,
        ];

        return $this->processOrder($request, $customerData);
    }
    
    /**
     * CORE LOGIC - DI SINI TEMPAT PENGIRIMAN QUEUE
     */
    private function processOrder(Request $request, array $customerData)
    {
        if (!$request->has('items')) {
            return redirect()->back()->with('error', 'Keranjang tidak ditemukan!');
        }

        try {
            DB::beginTransaction();

            $items = json_decode($request->items, true);
            if (json_last_error() !== JSON_ERROR_NONE || empty($items)) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Format data keranjang salah!');
            }

            $totalAmount = 0;
            $validItems = [];

            foreach ($items as $item) {
                if (!isset($item['menuId'], $item['quantity'])) continue;

                $menu = Menu::find($item['menuId']);
                if (!$menu) continue;

                $quantity = intval($item['quantity']);
                $price = floatval($menu->price);
                $itemTotal = $price * $quantity;
                $totalAmount += $itemTotal;

                $validItems[] = [
                    'menu_id'    => $menu->id,
                    'quantity'   => $quantity,
                    'unit_price' => $price,
                    'price'      => $itemTotal,
                    'notes'      => $item['item_notes'] ?? null, 
                ];
            }

            $orderNumber = 'ORD-' . date('Ymd') . '-' . mt_rand(10000, 99999);
            $queueNumber = Order::whereDate('created_at', date('Y-m-d'))->count() + 1;

            $order = Order::create(array_merge($customerData, [
                'order_number' => $orderNumber,
                'queue_number' => $queueNumber,
                'total_amount' => $totalAmount,
                'status'       => 'pending',
            ]));
            
            $order->orderItems()->createMany($validItems);

            // SELESAIKAN TRANSAKSI DB DULU
            DB::commit();

            // KIRIM NOTIFIKASI KE RABBITMQ SETELAH COMMIT BERHASIL
            SendNotificationJob::dispatch($order);

            return redirect()->route('order.success', ['order' => $order->id])
                             ->with('success', 'Pesanan #' . $orderNumber . ' Berhasil Dibuat!');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('💥 ORDER FAILED: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    // =================================================================
    // HISTORY & SUCCESS
    // =================================================================
    
    public function showSuccess($orderId)
    {
        $order = Order::with(['orderItems.menu'])->findOrFail($orderId); 
        return view('order-success', compact('order'));
    }

    public function showUserHistory()
    {
        if (!Auth::check()) return redirect()->route('login');

        $orders = Order::with(['orderItems.menu'])
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('order.history-form', compact('orders'));
    }

    public function getHistory(Request $request)
    {
        $request->validate([
            'customer_phone' => 'required|string',
        ]);

        $orders = Order::where('customer_phone', 'like', '%'.$request->customer_phone.'%')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('order.history', compact('orders'));
    }
}