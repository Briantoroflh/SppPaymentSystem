<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        return view('menu-custom');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Sequence', 'value' => 'sequence'],
            ['key' => 'Head Title', 'value' => 'head_title'],
            ['key' => 'Title', 'value' => 'title'],
            ['key' => 'Icon', 'value' => 'icon'],
            ['key' => 'Url', 'value' => 'url'],
        ];
    }

    public function getAll(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? '';

        // Query dasar
        $query = Menu::select('id', 'sequence', 'head_title', 'title', 'icon', 'url', 'created_by');

        // Search filter
        if (!empty($search)) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('head_title', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%");
        }

        // Total records
        $totalRecords = Menu::count();
        $filteredRecords = $query->count();

        // Pagination & sorting
        $menus = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $menus
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), Menu::rules());
        $validated['created_by'] = Auth::user()->name;

        try {
            if ($validated) {
                $menus = Menu::create($validated);
            } else {
                return response()->json(['message' => 'Sepertinya ada yang salah!']);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Menu berhasil terbuat!', 'data' => $menus]);
    }

    public function getById($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $menu
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), Menu::rules());
        $validated['created_by'] = Auth::user()->name;

        try {
            $menu = Menu::findOrFail($id);
            $menu->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil diupdate!',
                'data' => $menu
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        try {
            $menu->delete();
            return response()->json(['success' => true, 'message' => 'Menu berhasil di hapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
