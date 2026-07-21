<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    use \App\Traits\ImageUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::with('author')->latest('published_at')->get();
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the specified resource.
     */
    public function show(string $id)
    {
        $berita = News::with('author')->findOrFail($id);

        // Ambil berita terbaru untuk ditampilkan di sidebar (berita lainnya)
        $latestBerita = News::where('id', '!=', $id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        // Ambil berita utama pekan ini (7 hari terakhir) untuk preview
        $weeklyBerita = News::where('id', '!=', $id)
            ->where('status', 'published')
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(3)
            ->get();

        // Ambil berita untuk preview sebelah kiri
        $nextBerita = News::where('id', '!=', $id)
            ->where('status', 'published')
            ->inRandomOrder()
            ->first();

        return view('admin.news.show', compact('berita', 'latestBerita', 'weeklyBerita', 'nextBerita'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:10240',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        // Use auth user if available, otherwise fallback to first user or create one
        $user_id = Auth::id();
        if (!$user_id) {
            $user = User::first();
            if (!$user) {
                $user = User::create([
                    'name' => 'Admin Default',
                    'email' => 'admin@format-r.org',
                    'password' => bcrypt('password'),
                    'role' => 'admin'
                ]);
            }
            $user_id = $user->id;
        }
        $validated['author_id'] = $user_id;

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImageWebp($request->file('image'), 'news');
        }

        if ($validated['status'] == 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        News::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $berita = News::findOrFail($id);
        return view('admin.news.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $berita = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:10240',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            if ($berita->image) {
                Storage::disk('public')->delete($berita->image);
            }
            $validated['image'] = $this->uploadImageWebp($request->file('image'), 'news');
        }

        if ($validated['status'] == 'published' && empty($validated['published_at']) && $berita->status == 'draft') {
            $validated['published_at'] = now();
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $berita = News::findOrFail($id);
        
        if ($berita->image) {
            Storage::disk('public')->delete($berita->image);
        }
        
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
