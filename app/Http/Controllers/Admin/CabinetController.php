<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function index()
    {
        $cabinets = Cabinet::orderBy('start_year', 'desc')->get();
        return view('admin.cabinets.index', compact('cabinets'));
    }

    public function show(string $id)
    {
        $cabinet = Cabinet::with(['members.department'])->findOrFail($id);
        
        // Group members by department_id
        $groupedMembers = $cabinet->members->groupBy('department_id');

        return view('admin.cabinets.show', compact('cabinet', 'groupedMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'period' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'logo' => 'nullable|image|max:10240',
        ]);

        $startYear = (int) substr($request->period, 0, 4);

        $activeCabinet = Cabinet::where('is_active', true)->first();

        $isActive = false;
        if (!$activeCabinet) {
            $isActive = true;
        } else {
            if ($startYear >= $activeCabinet->start_year) {
                $isActive = true;
                Cabinet::where('is_active', true)->update(['is_active' => false]);
            }
        }
        
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
        }

        Cabinet::create([
            'name' => $request->name,
            'period' => $request->period,
            'start_year' => $startYear,
            'is_active' => $isActive,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'logo' => $logoPath,
        ]);

        return redirect()->route('admin.cabinets.index')->with('success', 'Kabinet berhasil ditambahkan.');
    }

    public function update(Request $request, Cabinet $cabinet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'period' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'logo' => 'nullable|image|max:10240',
        ]);

        $startYear = (int) substr($request->period, 0, 4);
        
        if ($request->hasFile('logo')) {
            if ($cabinet->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($cabinet->logo);
            }
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        $validated['start_year'] = $startYear;

        $cabinet->update($validated);

        return redirect()->route('admin.cabinets.index')->with('success', 'Kabinet berhasil diperbarui.');
    }

    public function destroy(Cabinet $cabinet)
    {
        if ($cabinet->logo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($cabinet->logo);
        }
        $cabinet->delete();
        return redirect()->route('admin.cabinets.index')->with('success', 'Kabinet berhasil dihapus.');
    }

    public function toggleActive(Cabinet $cabinet)
    {
        if (!$cabinet->is_active) {
            // Nonaktifkan semua yang lain
            Cabinet::where('is_active', true)->update(['is_active' => false]);
            $cabinet->update(['is_active' => true]);
            $msg = 'Kabinet berhasil diaktifkan.';
        } else {
            $cabinet->update(['is_active' => false]);
            $msg = 'Kabinet berhasil dinonaktifkan.';
        }

        return redirect()->route('admin.cabinets.index')->with('success', $msg);
    }
}
