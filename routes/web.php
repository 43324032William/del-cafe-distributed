<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

// =================================================================
// 1. PUBLIC ROUTES (Akses oleh Tamu atau Siapapun)
// =================================================================

Route::get('/', function () {
    return redirect()->route('menu.public');
})->name('home');

Route::get('/menu', [MenuController::class, 'publicMenu'])->name('menu.public');

// --- Public Order Routes (Tamu) ---

// Memproses pesanan dari tamu
Route::post('/order/guest', [OrderController::class, 'storeGuestOrder'])->name('order.guest.store');

// Halaman sukses pesanan
Route::get('/order/success/{order}', [OrderController::class, 'showSuccess'])->name('order.success');

// Detail pesanan
Route::get('/order/{order}/details', [OrderController::class, 'showOrderDetails'])->name('order.details');

// RIWAYAT PESANAN TAMU (FIXED: POST-Redirect-GET Pattern)
// 1. ROUTE GET untuk menampilkan form pencarian
Route::get('/order/history/form', [OrderController::class, 'showHistoryForm'])->name('order.history.form');

// 2. ROUTE POST untuk memproses data pencarian (Menerima input dari form)
Route::post('/order/history', [OrderController::class, 'getHistory'])->name('order.history');

// 3. ROUTE GET untuk menampilkan hasil pencarian (Destinasi redirect dari POST)
Route::get('/order/history/results', [OrderController::class, 'showHistoryResults'])->name('order.history.results');


// =================================================================
// 2. AUTHENTICATION (Login/Logout)
// =================================================================

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Auth routes (untuk user biasa)
require __DIR__.'/auth.php';


// =================================================================
// 3. AUTHENTICATED USER ROUTES (Hanya untuk User yang Login)
// =================================================================

Route::middleware(['auth'])->group(function () {
    // Riwayat Pesanan untuk User yang sedang login (Hanya pesanan milik user ini)
    Route::get('/my-orders', [OrderController::class, 'showUserHistory'])->name('user.history');
    
    // Endpoint untuk memproses pesanan dari user yang sudah login
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =================================================================
// 4. ADMIN ROUTES (Hanya untuk User Admin)
// =================================================================

// Catatan: Anda dapat mengganti 'auth' dengan middleware 'admin' jika sudah dibuat
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // Routes untuk menus (Menggunakan Resource-like structure)
    Route::get('/menus', [MenuController::class, 'index'])->name('admin.menus.index');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('admin.menus.create');
    Route::post('/menus', [MenuController::class, 'store'])->name('admin.menus.store');
    Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('admin.menus.edit');
    Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('admin.menus.update');
    Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('admin.menus.destroy');
    Route::post('/menus/{menu}/toggle-availability', [MenuController::class, 'toggleAvailability'])->name('admin.menus.toggle-availability');
    
    // Routes untuk orders
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
    
    // FITUR BARU: Route untuk menghapus pesanan (DELETE)
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    
    // FITUR BARU: Route untuk mencetak invoice (GET)
    // PERBAIKAN DI SINI: Mengganti 'print' menjadi 'printInvoice'
    Route::get('/orders/{order}/print', [OrderController::class, 'printInvoice'])->name('admin.orders.print');
    
    // Route untuk financial report
    Route::get('/financial-report', [FinancialReportController::class, 'index'])->name('admin.financial-report');
    
    // Debug/Test Route
    Route::get('/debug-orders', [OrderController::class, 'testOrder'])->name('admin.debug.orders');
    Route::post('/create-test-order', [OrderController::class, 'createTestOrder'])->name('admin.create.test.order');
});