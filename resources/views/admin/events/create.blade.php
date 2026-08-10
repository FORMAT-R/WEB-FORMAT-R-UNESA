@extends('admin.layouts.app')

@section('page-title', 'Buat Event Baru')

@section('content')
<div class="space-y-8" x-data="eventForm()" x-init="initSortable()">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Event Baru</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Tambahkan event atau program kerja baru</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn-secondary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7-7-7M14 18a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true" class="space-y-8">
        @csrf
        
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <section class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Informasi Event
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Event <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Latihan Dasar Kepemimpinan" value="{{ old('title') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Event <span class="text-red-500">*</span></label>
                    <select name="status" x-model="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                        <option value="upcoming">Akan Datang</option>
                        <option value="ongoing">Sedang Berlangsung</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>
                <div x-show="status === 'upcoming' || status === 'ongoing'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link Pendaftaran <span class="text-xs text-gray-500 font-normal ml-1">(Opsional)</span></label>
                    <input type="url" name="registration_link" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: https://forms.gle/..." value="{{ old('registration_link') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required value="{{ old('start_date') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required value="{{ old('end_date') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah Peserta</label>
                    <input type="number" name="participant_count" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" min="0" placeholder="Kosongkan jika belum ada" value="{{ old('participant_count') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="location" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required placeholder="Contoh: Ruang T12.01" value="{{ old('location') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Penyelenggara</label>
                    <select name="organizer" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <option value="FORMAT-R UNESA" {{ old('organizer') == 'FORMAT-R UNESA' ? 'selected' : '' }}>FORMAT-R UNESA (Default)</option>
                        @foreach($departments as $dept)
                            @php 
                                $singkatan = strtoupper($dept->abbreviation ?: $dept->slug); 
                                $isBph = ($singkatan === 'BPH');
                                $orgValue = $isBph ? 'BPH' : 'Departemen ' . $singkatan;
                            @endphp
                            <option value="{{ $orgValue }}" {{ old('organizer') == $orgValue ? 'selected' : '' }}>{{ $orgValue }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Singkat <span class="text-red-500">*</span></label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required placeholder="Penjelasan singkat mengenai event ini...">{{ old('description') }}</textarea>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Output / Luaran Kegiatan <span class="text-xs text-gray-500 font-normal ml-2">(Opsional)</span></label>
                <textarea name="output" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Tuliskan hasil kegiatan, pencapaian, atau output lainnya di sini...">{{ old('output') }}</textarea>
                <p class="text-xs text-gray-500 mt-2">Gunakan Enter untuk membuat paragraf baru. Teks ini akan muncul di bawah deskripsi pada halaman detail event.</p>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Poster / Gambar Utama</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Proposal (PDF)</label>
                    <input type="file" name="proposal_file" accept="application/pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File LPJ (PDF)</label>
                    <input type="file" name="lpj_file" accept="application/pdf" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            {{-- Panitia --}}
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Susunan Panitia</h3>
                    <button type="button" @click="addCommittee()" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200">
                        + Tambah Panitia
                    </button>
                </div>
                <div class="space-y-4" id="committees-container">
                    <template x-for="(c, idx) in committees" :key="c.id || idx">
                        <div class="committee-item grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 relative pl-10" :data-id="idx">
                            <!-- Drag Handle -->
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 cursor-move text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 handle">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                            </div>
                            
                            <!-- Hidden Sort Order -->
                            <input type="hidden" :name="`committees[${idx}][sort_order]`" :value="idx">

                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nama</label>
                                <input type="text" :name="`committees[${idx}][name]`" x-model="c.name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Jabatan (Ketik manual)</label>
                                <input type="text" :name="`committees[${idx}][role]`" x-model="c.role" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm">
                            </div>
                              <div>
                                  <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Foto Panitia <span class="font-normal">(Fokus Wajah)</span></label>
                                  <div class="flex items-center gap-3">
                                      <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-400">
                                          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                      </div>
                                      <input type="file" :name="`committees[${idx}][photo]`" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700">
                                  </div>
                              </div>
                            <div class="flex justify-end pb-1">
                                <button type="button" @click="removeCommittee(idx)" class="text-red-500 hover:text-red-700 p-2">Hapus</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Dokumentasi (Hanya untuk Ongoing & Completed) --}}
            <div x-show="status === 'ongoing' || status === 'completed'" class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700" style="display: none;">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Dokumentasi (Maks. 15)</h3>
                        <p class="text-xs text-gray-500">Berlaku untuk event berjalan & selesai.</p>
                    </div>
                    <button type="button" @click="addDoc()" x-show="documentations.length < 15" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200">
                        + Tambah Foto
                    </button>
                </div>
                <div class="space-y-4">
                    <template x-for="(d, idx) in documentations" :key="idx">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="md:col-span-1">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Foto Dokumentasi</label>
                                <input type="file" :name="`documentations[${idx}][photo]`" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700" required>
                            </div>
                            <div class="flex justify-end pb-1 h-full items-start">
                                <button type="button" @click="removeDoc(idx)" class="text-red-500 hover:text-red-700 p-2 bg-red-50 dark:bg-red-900/30 rounded-lg">Hapus Foto</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700 pt-6">
                <button type="button" @click="window.history.back()" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary px-8" :disabled="isSubmitting">
                    <span x-show="!isSubmitting">Simpan Event</span>
                    <span x-show="isSubmitting">Menyimpan...</span>
                </button>
            </div>
        </section>
    </form>
</div>

@push('scripts')
<script>
    function eventForm() {
        return {
            isSubmitting: false,
            status: '{{ old('status', 'upcoming') }}',
            committees: {!! json_encode(old('committees', [['name' => '', 'role' => '', 'photo' => null, 'sort_order' => 0]])) !!}.map((c, i) => ({...c, id: c.id || Date.now() + i})),
            documentations: {!! json_encode(old('documentations', [['title' => '', 'photo' => null]])) !!},
            addCommittee() { 
                this.committees.push({id: Date.now(), name: '', role: '', photo: null, sort_order: this.committees.length}); 
            },
            removeCommittee(idx) { this.committees.splice(idx, 1); },
            addDoc() { if(this.documentations.length < 15) this.documentations.push({title: '', photo: null}); },
            removeDoc(idx) { this.documentations.splice(idx, 1); },
            
            initSortable() {
                let el = document.getElementById('committees-container');
                if (el) {
                    Sortable.create(el, {
                        handle: '.handle',
                        animation: 150,
                        forceFallback: true, // This enables autoscroll in most edge cases
                        scroll: true, // Enable native scrolling
                        scrollSensitivity: 80, // Pixels from edge to trigger scroll
                        scrollSpeed: 20, // Scroll speed
                        bubbleScroll: true, // Allows window to scroll if parent is un-scrollable
                        onEnd: (evt) => {
                            // Update the array order based on DOM changes
                            let oldIndex = evt.oldIndex;
                            let newIndex = evt.newIndex;
                            if (oldIndex !== newIndex) {
                                // Extract the moved item
                                let movedItem = this.committees.splice(oldIndex, 1)[0];
                                // Insert it at the new position
                                this.committees.splice(newIndex, 0, movedItem);
                            }
                        }
                    });
                }
            }
        }
    }
</script>
@endpush
@endsection


