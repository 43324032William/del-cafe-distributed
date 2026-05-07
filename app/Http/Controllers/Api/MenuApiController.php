<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuApiController extends Controller
{
    public function index()
    {
        $menus = Menu::where('is_available', true)->get();

        return response()->json([
            'success' => true,
            'data' => $menus,
            'message' => 'Data menu berhasil diambil'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'required|string',
            'is_available' => 'boolean'
        ]);

        $menu = Menu::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'description' => $request->description,
            'is_available' => $request->boolean('is_available', true)
        ]);

        return response()->json([
            'success' => true,
            'data' => $menu,
            'message' => 'Menu berhasil dibuat'
        ], 201);
    }

    public function show(Menu $menu)
    {
        return response()->json([
            'success' => true,
            'data' => $menu,
            'message' => 'Data menu berhasil diambil'
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'price' => 'sometimes|integer|min:0',
            'description' => 'sometimes|string',
            'is_available' => 'boolean'
        ]);

        $menu->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $menu,
            'message' => 'Menu berhasil diupdate'
        ]);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus'
        ], 204);
    }

    public function byCategory($category)
    {
        $menus = Menu::where('category', $category)
            ->where('is_available', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $menus,
            'message' => "Data menu kategori {$category} berhasil diambil"
        ]);
    }
}