<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    use \App\Traits\ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::withCount('members')->get();
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'doc_image_1' => 'nullable|image|max:10240',
            'doc_image_2' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImageWebp($request->file('image'), 'departments');
        }
        if ($request->hasFile('doc_image_1')) {
            $validated['doc_image_1'] = $this->uploadImageWebp($request->file('doc_image_1'), 'departments');
        }
        if ($request->hasFile('doc_image_2')) {
            $validated['doc_image_2'] = $this->uploadImageWebp($request->file('doc_image_2'), 'departments');
        }

        $validated['slug'] = Str::slug($validated['name']);

        Department::create($validated);

        return redirect()->route('admin.departemen.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $cabinets = \App\Models\Cabinet::orderBy('start_year', 'desc')->get();
        $selectedCabinetId = $request->query('cabinet_id');
        
        if (!$selectedCabinetId) {
            $activeCabinet = $cabinets->where('is_active', true)->first();
            $selectedCabinetId = $activeCabinet ? $activeCabinet->id : null;
        }

        $department = Department::with(['members' => function($q) use ($selectedCabinetId) {
            if ($selectedCabinetId) {
                $q->where('cabinet_id', $selectedCabinetId);
            }
        }])->findOrFail($id);

        $selectedCabinet = $cabinets->where('id', $selectedCabinetId)->first();

        return view('admin.departments.show', compact('department', 'cabinets', 'selectedCabinetId', 'selectedCabinet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'doc_image_1' => 'nullable|image|max:10240',
            'doc_image_2' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            if ($department->image) {
                Storage::disk('public')->delete($department->image);
            }
            $validated['image'] = $this->uploadImageWebp($request->file('image'), 'departments');
        }
        if ($request->hasFile('doc_image_1')) {
            if ($department->doc_image_1) {
                Storage::disk('public')->delete($department->doc_image_1);
            }
            $validated['doc_image_1'] = $this->uploadImageWebp($request->file('doc_image_1'), 'departments');
        }
        if ($request->hasFile('doc_image_2')) {
            if ($department->doc_image_2) {
                Storage::disk('public')->delete($department->doc_image_2);
            }
            $validated['doc_image_2'] = $this->uploadImageWebp($request->file('doc_image_2'), 'departments');
        }

        $validated['slug'] = Str::slug($validated['name']);

        $department->update($validated);

        return redirect()->route('admin.departemen.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        if ($department->image) {
            Storage::disk('public')->delete($department->image);
        }
        $department->delete();

        return redirect()->route('admin.departemen.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
