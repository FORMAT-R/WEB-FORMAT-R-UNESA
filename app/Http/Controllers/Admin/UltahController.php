<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Birthday;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class UltahController extends Controller
{
    use \App\Traits\ImageUploadTrait;

    /**
     * Bersihkan cache ulang tahun di homepage agar data baru langsung muncul pada hari H.
     */
    private function forgetUltahCache(): void
    {
        $todayKey = 'home_ultah_today_' . Carbon::now()->format('m-d');
        $monthKey = 'home_ultah_' . Carbon::now()->month;
        Cache::forget($todayKey);
        Cache::forget($monthKey);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $birthdays = Birthday::with('member.department')->latest()->get();
        return view('admin.birthdays.index', compact('birthdays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::with('department')->orderBy('name')->get();
        return view('admin.birthdays.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|max:10240',
            'celebration_status' => 'required|in:belum_dirayakan,sudah_dirayakan',
            'message' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'birthdays');
        }

        Birthday::create($validated);

        $this->forgetUltahCache();

        return redirect()->route('admin.ultah.index')->with('success', 'Data ulang tahun berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $birthday = Birthday::findOrFail($id);
        $members = Member::with('department')->orderBy('name')->get();
        
        return view('admin.birthdays.edit', compact('birthday', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $birthday = Birthday::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|max:10240',
            'celebration_status' => 'required|in:belum_dirayakan,sudah_dirayakan',
            'message' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada dan bukan dari tabel members
            if ($birthday->photo && !Member::where('photo', $birthday->photo)->exists()) {
                Storage::disk('public')->delete($birthday->photo);
            }
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'birthdays');
        }

        $birthday->update($validated);

        $this->forgetUltahCache();

        return redirect()->route('admin.ultah.index')->with('success', 'Data ulang tahun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $birthday = Birthday::findOrFail($id);
        
        if ($birthday->photo && !Member::where('photo', $birthday->photo)->exists()) {
            Storage::disk('public')->delete($birthday->photo);
        }
        
        $birthday->delete();

        $this->forgetUltahCache();

        return redirect()->route('admin.ultah.index')->with('success', 'Data ulang tahun berhasil dihapus.');
    }
}

