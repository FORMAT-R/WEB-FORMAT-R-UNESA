<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembina;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Storage;

class PembinaController extends Controller
{
    use ImageUploadTrait;

    public function index()
    {
        $pembinas = Pembina::latest()->get();
        return view('admin.pembinas.index', compact('pembinas'));
    }

    public function create()
    {
        return view('admin.pembinas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'term_period' => 'required|string|max:255',
            'biography' => 'required|string',
            'photo' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'pembinas');
        }

        if (Pembina::count() === 0) {
            $validated['is_active'] = true;
        }

        Pembina::create($validated);

        return redirect()->route('admin.pembinas.index')->with('success', 'Data Pembina berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $pembina = Pembina::findOrFail($id);
        return view('admin.pembinas.edit', compact('pembina'));
    }

    public function update(Request $request, string $id)
    {
        $pembina = Pembina::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'term_period' => 'required|string|max:255',
            'biography' => 'required|string',
            'photo' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            if ($pembina->photo) {
                Storage::disk('public')->delete($pembina->photo);
            }
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'pembinas');
        }

        $pembina->update($validated);

        return redirect()->route('admin.pembinas.index')->with('success', 'Data Pembina berhasil diperbarui.');
    }

    public function toggleActive(string $id)
    {
        $pembina = Pembina::findOrFail($id);
        
        Pembina::query()->update(['is_active' => false]);
        
        $pembina->update(['is_active' => true]);

        return redirect()->route('admin.pembinas.index')->with('success', 'Status Pembina Aktif berhasil diubah.');
    }

    public function destroy(string $id)
    {
        $pembina = Pembina::findOrFail($id);
        
        if ($pembina->photo) {
            Storage::disk('public')->delete($pembina->photo);
        }
        
        $pembina->delete();

        return redirect()->route('admin.pembinas.index')->with('success', 'Data Pembina berhasil dihapus.');
    }
}
