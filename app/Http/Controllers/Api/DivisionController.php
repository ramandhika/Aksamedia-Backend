<?php

namespace App\Http\Controllers\Api;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::when($request->name, function ($query, $name) {
            return $query->where('name', 'like', "%{$name}%");
        })->paginate(10);

        if ($divisions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Divisi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Divisi berhasil diambil',
            'data' => $divisions->map(function ($division) {
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                ];
            }),
            'pagination' => [
                'halaman_sekarang' => $divisions->currentPage(),
                'halaman_terakhir' => $divisions->lastPage(),
                'per_halaman' => $divisions->perPage(),
                'total' => $divisions->total(),
            ],
        ], 200);
    }
}
