<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\EventCommittee;
use App\Models\EventDocumentation;

class EventController extends Controller
{
    use \App\Traits\ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::latest('start_date')->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = \App\Models\Department::all();
        return view('admin.events.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'output' => 'nullable|string',
            'location' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:upcoming,ongoing,completed',
            'registration_link' => 'nullable|url|max:255',
            'image' => 'nullable|image|max:10240',
            'lpj_file' => 'nullable|file|mimes:pdf|max:5120',
            'proposal_file' => 'nullable|file|mimes:pdf|max:5120',
            'participant_count' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImageWebp($request->file('image'), 'events/images');
        }
        
        if ($request->hasFile('proposal_file')) {
            $validated['proposal_file'] = $request->file('proposal_file')->store('events/documents', 'public');
        }
        
        if ($request->hasFile('lpj_file')) {
            $validated['lpj_file'] = $request->file('lpj_file')->store('events/documents', 'public');
        }

        $event = Event::create($validated);

        $this->saveRelations($request, $event);

        return redirect()->route('admin.events.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::with(['documentations', 'committees'])->findOrFail($id);
        $departments = \App\Models\Department::all();
        return view('admin.events.edit', compact('event', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'output' => 'nullable|string',
            'location' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:upcoming,ongoing,completed',
            'registration_link' => 'nullable|url|max:255',
            'image' => 'nullable|image|max:10240',
            'lpj_file' => 'nullable|file|mimes:pdf|max:5120',
            'proposal_file' => 'nullable|file|mimes:pdf|max:5120',
            'participant_count' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $this->uploadImageWebp($request->file('image'), 'events/images');
        }
        
        if ($request->hasFile('proposal_file')) {
            if ($event->proposal_file) {
                Storage::disk('public')->delete($event->proposal_file);
            }
            $validated['proposal_file'] = $request->file('proposal_file')->store('events/documents', 'public');
        }
        
        if ($request->hasFile('lpj_file')) {
            if ($event->lpj_file) {
                Storage::disk('public')->delete($event->lpj_file);
            }
            $validated['lpj_file'] = $request->file('lpj_file')->store('events/documents', 'public');
        }

        $event->update($validated);

        $this->saveRelations($request, $event);

        return redirect()->route('admin.events.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    private function saveRelations(Request $request, Event $event)
    {
        // Save Committees
        if ($request->has('committees')) {
            $existingCommitteeIds = $event->committees->pluck('id')->toArray();
            $updatedCommitteeIds = [];

            foreach ($request->committees as $idx => $c) {
                if (empty($c['name']) || empty($c['role'])) continue;

                $data = [
                    'name' => $c['name'],
                    'role' => $c['role'],
                ];

                if (isset($c['photo']) && $request->hasFile("committees.{$idx}.photo")) {
                    $data['photo'] = $this->uploadImageWebp($c['photo'], 'events/committees');
                }

                if (isset($c['id']) && $c['id']) {
                    $committee = EventCommittee::find($c['id']);
                    if ($committee) {
                        $committee->update($data);
                        $updatedCommitteeIds[] = $committee->id;
                    }
                } else {
                    $newCommittee = $event->committees()->create($data);
                    $updatedCommitteeIds[] = $newCommittee->id;
                }
            }

            // Delete removed committees
            $toDelete = array_diff($existingCommitteeIds, $updatedCommitteeIds);
            foreach ($toDelete as $id) {
                $c = EventCommittee::find($id);
                if ($c) {
                    if ($c->photo) Storage::disk('public')->delete($c->photo);
                    $c->delete();
                }
            }
        }

        // Save Documentations (only if ongoing or completed)
        if (in_array($event->status, ['ongoing', 'completed']) && $request->has('documentations')) {
            $existingDocIds = $event->documentations->pluck('id')->toArray();
            $updatedDocIds = [];

            foreach ($request->documentations as $idx => $d) {
                if ($idx >= 10) break; // Limit to 10 photos

                $data = [
                    'title' => $d['title'] ?? null,
                ];

                $hasPhoto = isset($d['photo']) && $request->hasFile("documentations.{$idx}.photo");
                
                // If new and no photo, skip
                if (!isset($d['id']) && !$hasPhoto) continue;

                if ($hasPhoto) {
                    $data['photo'] = $this->uploadImageWebp($d['photo'], 'events/documentations');
                }

                if (isset($d['id']) && $d['id']) {
                    $doc = EventDocumentation::find($d['id']);
                    if ($doc) {
                        $doc->update($data);
                        $updatedDocIds[] = $doc->id;
                    }
                } else {
                    $newDoc = $event->documentations()->create($data);
                    $updatedDocIds[] = $newDoc->id;
                }
            }

            // Delete removed documentations
            $toDeleteDocs = array_diff($existingDocIds, $updatedDocIds);
            foreach ($toDeleteDocs as $id) {
                $d = EventDocumentation::find($id);
                if ($d) {
                    if ($d->photo) Storage::disk('public')->delete($d->photo);
                    $d->delete();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        if ($event->proposal_file) {
            Storage::disk('public')->delete($event->proposal_file);
        }
        if ($event->lpj_file) {
            Storage::disk('public')->delete($event->lpj_file);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Agenda berhasil dihapus.');
    }
}

