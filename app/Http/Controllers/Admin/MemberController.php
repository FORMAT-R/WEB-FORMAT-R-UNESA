<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Jobs\ProcessMemberPhotoBackground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    use \App\Traits\ImageUploadTrait;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'cabinet_id' => 'required|exists:cabinets,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|max:10240',
        ]);

        $hasNewPhoto = false;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'members');
            $hasNewPhoto = true;
        }

        $member = Member::create($validated);

        if ($hasNewPhoto) {
            // Jalankan remove background di background task via Queue
            ProcessMemberPhotoBackground::dispatch($member->id);
        }

        return redirect()->route('admin.departemen.show', $request->department_id)
            ->with('success', 'Anggota berhasil ditambahkan. Foto sedang diproses AI di latar belakang.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'cabinet_id' => 'required|exists:cabinets,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|max:10240',
        ]);

        $hasNewPhoto = false;

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            if ($member->photo_nobg) {
                Storage::disk('public')->delete($member->photo_nobg);
            }
            
            // Set ke null sementara saat diupdate sebelum di-generate baru oleh Queue
            $validated['photo_nobg'] = null;
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'members');
            $hasNewPhoto = true;
        }

        $member->update($validated);

        if ($hasNewPhoto) {
            // Jalankan remove background di background task via Queue
            ProcessMemberPhotoBackground::dispatch($member->id);
        }

        return redirect()->route('admin.departemen.show', $request->department_id)
            ->with('success', 'Anggota berhasil diperbarui. Foto sedang diproses AI di latar belakang.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $departmentId = $member->department_id;
        
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }
        if ($member->photo_nobg) {
            Storage::disk('public')->delete($member->photo_nobg);
        }
        $member->delete();

        return redirect()->route('admin.departemen.show', $departmentId)->with('success', 'Anggota berhasil dihapus.');
    }
}
