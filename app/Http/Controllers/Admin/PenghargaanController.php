<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BestOfficer;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenghargaanController extends Controller
{
    use \App\Traits\ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $awards = BestOfficer::with('member')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return view('admin.awards.index', compact('awards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::with('department')->get();
        return view('admin.awards.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'name' => 'required_without:member_id|string|max:255|nullable',
            'department' => 'required_without:member_id|string|max:255|nullable',
            'photo' => 'nullable|image|max:10240',
            'month' => 'required|string|max:50',
            'year' => 'required|integer',
        ]);

        $validated['reason'] = '-'; // Default value since it's removed from form

        if ($request->filled('member_id')) {
            $member = Member::with('department')->findOrFail($request->member_id);
            $validated['name'] = $member->name;
            $validated['department'] = $member->department->name ?? '';
            
            if (!$request->hasFile('photo')) {
                $validated['photo'] = $member->photo;
            }
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'awards');
        }

        BestOfficer::create($validated);

        return redirect()->route('admin.penghargaan.index')->with('success', 'Penghargaan berhasil ditambahkan.');
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
        $award = BestOfficer::findOrFail($id);
        $members = Member::with('department')->get();
        return view('admin.awards.edit', compact('award', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $award = BestOfficer::findOrFail($id);

        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'name' => 'required_without:member_id|string|max:255|nullable',
            'department' => 'required_without:member_id|string|max:255|nullable',
            'photo' => 'nullable|image|max:10240',
            'month' => 'required|string|max:50',
            'year' => 'required|integer',
        ]);

        $validated['reason'] = '-'; // Default value since it's removed from form

        if ($request->filled('member_id')) {
            $member = Member::with('department')->findOrFail($request->member_id);
            $validated['name'] = $member->name;
            $validated['department'] = $member->department->name ?? '';
            
            if (!$request->hasFile('photo') && !$award->photo) {
                $validated['photo'] = $member->photo;
            }
        }

        if ($request->hasFile('photo')) {
            if ($award->photo && !Member::where('photo', $award->photo)->exists()) {
                Storage::disk('public')->delete($award->photo);
            }
            $validated['photo'] = $this->uploadImageWebp($request->file('photo'), 'awards');
        }

        $award->update($validated);

        return redirect()->route('admin.penghargaan.index')->with('success', 'Penghargaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $award = BestOfficer::findOrFail($id);
        
        if ($award->photo && !Member::where('photo', $award->photo)->exists()) {
            Storage::disk('public')->delete($award->photo);
        }
        
        $award->delete();

        return redirect()->route('admin.penghargaan.index')->with('success', 'Penghargaan berhasil dihapus.');
    }
}
