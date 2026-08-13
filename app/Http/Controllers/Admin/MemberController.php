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
            'department_id'         => 'required|exists:departments,id',
            'cabinet_id'            => 'required|exists:cabinets,id',
            'name'                  => 'required|string|max:255',
            'position'              => 'required|string|max:255',
            'photo'                 => 'nullable|image|max:10240',
            'photo_sorotan'         => 'nullable|image|max:10240',
            'photo_sorotan_cropped' => 'nullable|string', // base64 dari browser face-crop
        ]);

        $faceCropped   = $request->input('face_cropped') === '1';
        $hasNewSorotan = false;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'members');
        }

        // Jika foto sudah di-crop wajah di browser (base64), simpan dari base64
        if ($faceCropped && $request->filled('photo_sorotan_cropped')) {
            $saved = $this->saveBase64Webp($request->input('photo_sorotan_cropped'), 'members_sorotan');
            if ($saved) {
                $validated['photo_sorotan'] = $saved;
                $hasNewSorotan = true;
            }
        } elseif ($request->hasFile('photo_sorotan')) {
            // Fallback: foto asli tanpa face-crop di browser
            $validated['photo_sorotan'] = $this->uploadImageWebp($request->file('photo_sorotan'), 'members_sorotan');
            $hasNewSorotan = true;
            $faceCropped   = false; // pastikan flag false untuk fallback
        }

        $member = Member::create($validated);

        if ($hasNewSorotan) {
            ProcessMemberPhotoBackground::dispatch($member->id, $faceCropped);
        }

        return redirect()->route('admin.departemen.show', $request->department_id)
            ->with('success', 'Anggota berhasil ditambahkan. Foto sorotan sedang diproses AI di latar belakang.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'department_id'         => 'required|exists:departments,id',
            'cabinet_id'            => 'required|exists:cabinets,id',
            'name'                  => 'required|string|max:255',
            'position'              => 'required|string|max:255',
            'photo'                 => 'nullable|image|max:10240',
            'photo_sorotan'         => 'nullable|image|max:10240',
            'photo_sorotan_cropped' => 'nullable|string',
        ]);

        $faceCropped   = $request->input('face_cropped') === '1';
        $hasNewSorotan = false;

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'members');
        }

        // Jika foto sudah di-crop wajah di browser (base64)
        if ($faceCropped && $request->filled('photo_sorotan_cropped')) {
            if ($member->photo_sorotan) Storage::disk('public')->delete($member->photo_sorotan);
            if ($member->photo_nobg)    Storage::disk('public')->delete($member->photo_nobg);

            $saved = $this->saveBase64Webp($request->input('photo_sorotan_cropped'), 'members_sorotan');
            if ($saved) {
                $validated['photo_sorotan'] = $saved;
                $validated['photo_nobg']    = null;
                $hasNewSorotan = true;
            }
        } elseif ($request->hasFile('photo_sorotan')) {
            // Fallback: foto asli tanpa face-crop
            if ($member->photo_sorotan) Storage::disk('public')->delete($member->photo_sorotan);
            if ($member->photo_nobg)    Storage::disk('public')->delete($member->photo_nobg);

            $validated['photo_nobg']    = null;
            $validated['photo_sorotan'] = $this->uploadImageWebp($request->file('photo_sorotan'), 'members_sorotan');
            $hasNewSorotan = true;
            $faceCropped   = false;
        }

        $member->update($validated);

        if ($hasNewSorotan) {
            ProcessMemberPhotoBackground::dispatch($member->id, $faceCropped);
        }

        return redirect()->route('admin.departemen.show', $request->department_id)
            ->with('success', 'Anggota berhasil diperbarui. Foto sorotan sedang diproses AI di latar belakang.');
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
        if ($member->photo_sorotan) {
            Storage::disk('public')->delete($member->photo_sorotan);
        }
        if ($member->photo_nobg) {
            Storage::disk('public')->delete($member->photo_nobg);
        }
        $member->delete();

        return redirect()->route('admin.departemen.show', $departmentId)->with('success', 'Anggota berhasil dihapus.');
    }
}
