<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with('division')
            ->when($request->name, function ($query, $name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->when($request->division_id, function ($query, $division_id) {
                return $query->where('division_id', $division_id);
            })
            ->paginate(10);

        if ($employees->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data karyawan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diambil',
            'data' => [
                'employees' => $employees->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'image' => $employee->image,
                        'name' => $employee->name,
                        'phone' => $employee->phone,
                        'division' => [
                            'id' => $employee->division->id,
                            'name' => $employee->division->name,
                        ],
                        'position' => $employee->position,
                    ];
                }),
            ],
            'pagination' => [
                'halaman_sekarang' => $employees->currentPage(),
                'halaman_terakhir' => $employees->lastPage(),
                'per_halaman' => $employees->perPage(),
                'total' => $employees->total(),
                'links' => [
                    'halaman_sebelumnya' => $employees->previousPageUrl(),
                    'halaman_sekarang' => $employees->url($employees->currentPage()),
                    'halaman_selanjutnya' => $employees->nextPageUrl(),
                ],
            ],
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'division' => 'required|exists:divisions,id',
            'position' => 'required',
            'image' => 'nullable|image|max:5120',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('public/Employee') : null;

        if ($imagePath) {
            $imagePath = str_replace('public/', 'storage/', $imagePath);
        }

        Employee::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'division_id' => $request->division,
            'position' => $request->position,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil ditambahkan',
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'division' => 'required|exists:divisions,id',
            'position' => 'required',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('public/Employee');
            $imagePath = str_replace('public/', 'storage/', $imagePath);
            $employee->update(['image' => $imagePath]);
        }

        $employee->update($request->only('name', 'phone', 'division_id', 'position'));

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diubah',
        ]);
    }

    public function destroy(Employee $employee)
    {
        if ($employee->image) {
            Storage::delete(str_replace('storage/', 'public/', $employee->image));
        }

        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil dihapus',
        ]);
    }
}
