<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Show medicines for Admin/Nurse (with CRUD).
     */
    public function index()
    {
        $medicines = Medicine::latest()->paginate(10);
        return view('medicines.index', compact('medicines'));
    }

    /**
     * Show medicines for Students (read-only).
     */
    public function studentIndex()
    {
        $medicines = Medicine::latest()->paginate(10);
        return view('students.medicines.index', compact('medicines'));
    }

    /**
     * Store a new medicine (for nurse/admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Medicine::create($request->all());

        return response()->json(['success' => 'Medicine added successfully!']);
    }

    /**
     * Update an existing medicine.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $medicine->update($request->all());

        return response()->json(['success' => 'Medicine updated successfully!']);
    }

    /**
     * Delete a medicine.
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return response()->json(['success' => 'Medicine deleted successfully!']);
    }
}
