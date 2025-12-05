<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    public function index()
    {
        return view('region-management');
    }

    public static function headerColumn()
    {
        return [
            ['key' => 'Name', 'value' => 'name'],
            ['key' => 'Longitude', 'value' => 'longitude'],
            ['key' => 'Latitude', 'value' => 'latitude'],
        ];
    }

    public function getAll(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? '';

        // Ensure length is positive
        if ($length <= 0 || $length > 1000) {
            $length = 10;
        }

        // Query dasar
        $query = Region::select('id', 'name', 'longitude', 'latitude');

        // Search filter
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('longitude', 'like', "%{$search}%")
                ->orWhere('latitude', 'like', "%{$search}%");
        }

        // Total records
        $totalRecords = Region::count();
        $filteredRecords = $query->count();

        // Pagination & sorting
        $regions = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $regions
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::validate($request->all(), Region::rules());

        try {
            if ($validated) {
                $region = Region::create($validated);
            } else {
                return response()->json(['message' => 'Sepertinya ada yang salah!']);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Region berhasil terbuat!', 'data' => $region]);
    }

    public function getById($id)
    {
        try {
            $region = Region::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $region
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Region tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::validate($request->all(), Region::rules());

        try {
            $region = Region::findOrFail($id);
            $region->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Region berhasil diupdate!',
                'data' => $region
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        try {
            $region->delete();
            return response()->json(['success' => true, 'message' => 'Region berhasil di hapus!']);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
