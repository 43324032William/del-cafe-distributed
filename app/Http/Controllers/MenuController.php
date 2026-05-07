<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    /**
     * Display public menu for guests
     */
    public function publicMenu()
    {
        // DEBUG: Log semua menu di database
        Log::info('=== LOADING PUBLIC MENU ===');
        $allMenus = Menu::all();
        
        Log::info('ALL MENUS IN DATABASE:', [
            'total_count' => $allMenus->count(),
            'menus' => $allMenus->map(function($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'category' => $menu->category,
                    'price' => $menu->price,
                    'is_available' => $menu->is_available,
                    'created_at' => $menu->created_at
                ];
            })->toArray()
        ]);

        // Query menu yang available
        $menus = Menu::where('is_available', true)
                    ->orderBy('category')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('category');

        Log::info('AVAILABLE MENUS FOR PUBLIC:', [
            'available_count' => $menus->flatten()->count(),
            'categories_found' => array_keys($menus->toArray())
        ]);

        $categories = [
            'makanan' => 'Makanan',
            'minuman' => 'Minuman', 
            'snack' => 'Snack',
            'dessert' => 'Dessert'
        ];

        return view('public-menu', compact('menus', 'categories'));
    }

    /**
     * Display a listing of the resource for admin
     */
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $search = $request->get('search');
        
        $menus = Menu::when($search, function($query) use ($search) {
                        return $query->where('name', 'like', '%'.$search.'%')
                                    ->orWhere('description', 'like', '%'.$search.'%')
                                    ->orWhere('category', 'like', '%'.$search.'%');
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $categories = [
            'makanan' => 'Makanan',
            'minuman' => 'Minuman',
            'snack' => 'Snack',
            'dessert' => 'Dessert'
        ];

        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:1000|max:1000000',
            'category' => 'required|string|in:makanan,minuman,snack,dessert',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama menu wajib diisi',
            'description.required' => 'Deskripsi menu wajib diisi',
            'price.required' => 'Harga menu wajib diisi',
            'price.min' => 'Harga minimal Rp 1.000',
            'price.max' => 'Harga maksimal Rp 1.000.000',
            'category.required' => 'Kategori menu wajib dipilih',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Debug request data
        Log::info('CREATING NEW MENU:', [
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'is_available' => $request->has('is_available') ? 'true' : 'false'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu-images', 'public');
            Log::info('Menu image stored:', ['path' => $imagePath]);
        }

        // Create menu dengan logging
        try {
            $menu = Menu::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'category' => $request->category,
                'image' => $imagePath,
                'is_available' => $request->has('is_available'),
            ]);

            Log::info('MENU CREATED SUCCESSFULLY:', [
                'id' => $menu->id,
                'name' => $menu->name,
                'is_available' => $menu->is_available
            ]);

            return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('FAILED TO CREATE MENU:', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Gagal menambahkan menu: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $categories = [
            'makanan' => 'Makanan',
            'minuman' => 'Minuman',
            'snack' => 'Snack',
            'dessert' => 'Dessert'
        ];

        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:1000|max:1000000',
            'category' => 'required|string|in:makanan,minuman,snack,dessert',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama menu wajib diisi',
            'description.required' => 'Deskripsi menu wajib diisi',
            'price.required' => 'Harga menu wajib diisi',
            'price.min' => 'Harga minimal Rp 1.000',
            'price.max' => 'Harga maksimal Rp 1.000.000',
            'category.required' => 'Kategori menu wajib dipilih',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Debug update data
        Log::info('UPDATING MENU:', [
            'menu_id' => $menu->id,
            'name' => $request->name,
            'is_available' => $request->has('is_available') ? 'true' : 'false',
            'old_availability' => $menu->is_available
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'is_available' => $request->has('is_available'),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
                Log::info('Old menu image deleted:', ['path' => $menu->image]);
            }
            $data['image'] = $request->file('image')->store('menu-images', 'public');
            Log::info('New menu image stored:', ['path' => $data['image']]);
        }

        try {
            $menu->update($data);

            Log::info('MENU UPDATED SUCCESSFULLY:', [
                'id' => $menu->id,
                'name' => $menu->name,
                'is_available' => $menu->is_available
            ]);

            return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diupdate!');

        } catch (\Exception $e) {
            Log::error('FAILED TO UPDATE MENU:', [
                'menu_id' => $menu->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal mengupdate menu: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        Log::info('DELETING MENU:', [
            'id' => $menu->id,
            'name' => $menu->name
        ]);

        // Delete image if exists
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
            Log::info('Menu image deleted:', ['path' => $menu->image]);
        }

        $menu->delete();

        Log::info('MENU DELETED SUCCESSFULLY:', ['id' => $menu->id]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * Toggle menu availability
     */
    public function toggleAvailability(Menu $menu)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        $newStatus = !$menu->is_available;

        Log::info('TOGGLING MENU AVAILABILITY:', [
            'menu_id' => $menu->id,
            'menu_name' => $menu->name,
            'old_status' => $menu->is_available,
            'new_status' => $newStatus
        ]);

        $menu->update([
            'is_available' => $newStatus
        ]);

        $status = $newStatus ? 'Tersedia' : 'Habis';
        
        Log::info('MENU AVAILABILITY UPDATED:', [
            'menu_id' => $menu->id,
            'new_status' => $status
        ]);

        return redirect()->back()->with('success', "Status menu berhasil diubah menjadi {$status}!");
    }

    /**
     * Debug method to check menu data
     */
    public function debugMenus()
    {
        $allMenus = Menu::all();
        $availableMenus = Menu::where('is_available', true)->get();
        
        return response()->json([
            'all_menus_count' => $allMenus->count(),
            'available_menus_count' => $availableMenus->count(),
            'all_menus' => $allMenus->map(function($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'category' => $menu->category,
                    'price' => $menu->price,
                    'is_available' => $menu->is_available,
                    'created_at' => $menu->created_at
                ];
            }),
            'available_menus' => $availableMenus->map(function($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'category' => $menu->category
                ];
            })
        ]);
    }
}