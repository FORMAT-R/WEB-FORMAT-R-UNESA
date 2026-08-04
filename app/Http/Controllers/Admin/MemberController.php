<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
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

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'members');
        }

        Member::create($validated);

        return redirect()->route('admin.departemen.show', $request->department_id)->with('success', 'Anggota berhasil ditambahkan.');
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

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'members');
        }

        $member->update($validated);

        return redirect()->route('admin.departemen.show', $request->department_id)->with('success', 'Anggota berhasil diperbarui.');
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
        $member->delete();

        return redirect()->route('admin.departemen.show', $departmentId)->with('success', 'Anggota berhasil dihapus.');
    }
}
