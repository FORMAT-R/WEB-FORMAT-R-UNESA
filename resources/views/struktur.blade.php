@extends('layouts.app')

@section('title', 'Struktur Organisasi - FORMAT-R UNESA')

@section('content')
<div class="org-page">
    <div class="container">
        <div class="sec-head" style="text-align: center; margin-bottom: 60px; margin-left: auto; margin-right: auto;">
            <span class="eyebrow" style="margin: 0 auto;">Pengurus Aktif</span>
            <h2 style="text-align: center;">Struktur Organisasi</h2>
            <p style="text-align: center; margin-left: auto; margin-right: auto;">Susunan pengurus FORMAT-R UNESA periode {{ get_setting('cabinetName', 'Saat Ini') }}.</p>
        </div>
    </div> <!-- Tutup container di sini -->

    <div class="org-tree-wrapper">
            <div class="org-tree">
                
                {{-- LEVEL 1: PEMBINA --}}
                @if($pembina)
                <div class="org-level level-pembina">
                    <div class="org-node">
                        <div class="org-photo">
                            @if($pembina->photo)
                                <img src="{{ Storage::url($pembina->photo) }}" alt="Pembina">
                            @else
                                <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                            @endif
                        </div>
                        <div class="org-info">
                            <h4>{{ $pembina->name }}</h4>
                            <span>Pembina</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- LEVEL 2: KETUA UMUM --}}
                @if($ketum)
                <div class="org-level level-ketum">
                    <div class="org-node">
                        <div class="org-photo">
                            @if($ketum->photo)
                                <img src="{{ Storage::url($ketum->photo) }}" alt="Ketua Umum">
                            @else
                                <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                            @endif
                        </div>
                        <div class="org-info">
                            <h4>{{ $ketum->name }}</h4>
                            <span>Ketua Umum</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- LEVEL 3: WAKIL KETUA UMUM --}}
                @if($waketum)
                <div class="org-level level-waketum">
                    <div class="org-node">
                        <div class="org-photo">
                            @if($waketum->photo)
                                <img src="{{ Storage::url($waketum->photo) }}" alt="Wakil Ketua">
                            @else
                                <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                            @endif
                        </div>
                        <div class="org-info">
                            <h4>{{ $waketum->name }}</h4>
                            <span>Wakil Ketua Umum</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- LEVEL 4: SEKRETARIS & BENDAHARA (CABANG) --}}
                <div class="org-level level-sekben">
                    <div class="org-branch-group">
                        
                        {{-- Kiri: Sekretaris --}}
                        <div class="org-branch branch-left">
                            <div class="org-node">
                                <div class="org-photo">
                                    @if($sekretaris['umum'] && $sekretaris['umum']->photo)
                                        <img src="{{ Storage::url($sekretaris['umum']->photo) }}" alt="Sekretaris Umum">
                                    @else
                                        <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                    @endif
                                </div>
                                <div class="org-info">
                                    <h4>{{ $sekretaris['umum']->name ?? 'Nama Belum Diatur' }}</h4>
                                    <span>Sekretaris Umum</span>
                                </div>
                            </div>

                            <div class="org-sub-branch">
                                @if($sekretaris['satu'])
                                <div class="org-node sub-node">
                                    <div class="org-photo">
                                        @if($sekretaris['satu']->photo)
                                            <img src="{{ Storage::url($sekretaris['satu']->photo) }}" alt="Sekretaris 1">
                                        @else
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </div>
                                    <div class="org-info">
                                        <h4>{{ $sekretaris['satu']->name }}</h4>
                                        <span>Sekretaris 1</span>
                                    </div>
                                </div>
                                @endif
                                @if($sekretaris['dua'])
                                <div class="org-node sub-node">
                                    <div class="org-photo">
                                        @if($sekretaris['dua']->photo)
                                            <img src="{{ Storage::url($sekretaris['dua']->photo) }}" alt="Sekretaris 2">
                                        @else
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </div>
                                    <div class="org-info">
                                        <h4>{{ $sekretaris['dua']->name }}</h4>
                                        <span>Sekretaris 2</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Kanan: Bendahara --}}
                        <div class="org-branch branch-right">
                            <div class="org-node">
                                <div class="org-photo">
                                    @if($bendahara['umum'] && $bendahara['umum']->photo)
                                        <img src="{{ Storage::url($bendahara['umum']->photo) }}" alt="Bendahara Umum">
                                    @else
                                        <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                    @endif
                                </div>
                                <div class="org-info">
                                    <h4>{{ $bendahara['umum']->name ?? 'Nama Belum Diatur' }}</h4>
                                    <span>Bendahara Umum</span>
                                </div>
                            </div>

                            <div class="org-sub-branch">
                                @if($bendahara['satu'])
                                <div class="org-node sub-node">
                                    <div class="org-photo">
                                        @if($bendahara['satu']->photo)
                                            <img src="{{ Storage::url($bendahara['satu']->photo) }}" alt="Bendahara 1">
                                        @else
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </div>
                                    <div class="org-info">
                                        <h4>{{ $bendahara['satu']->name }}</h4>
                                        <span>Bendahara 1</span>
                                    </div>
                                </div>
                                @endif
                                @if($bendahara['dua'])
                                <div class="org-node sub-node">
                                    <div class="org-photo">
                                        @if($bendahara['dua']->photo)
                                            <img src="{{ Storage::url($bendahara['dua']->photo) }}" alt="Bendahara 2">
                                        @else
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </div>
                                    <div class="org-info">
                                        <h4>{{ $bendahara['dua']->name }}</h4>
                                        <span>Bendahara 2</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                {{-- LEVEL 5: DEPARTEMEN --}}
                @if($departments->count() > 0)
                <div class="org-level level-dept">
                    <div class="dept-wrapper">
                        @foreach($departments as $dept)
                            @php
                                $deptMembers = $members->where('department_id', $dept->id);
                                $kadep = $deptMembers->firstWhere('position', 'Ketua Departemen');
                                $wakadep = $deptMembers->firstWhere('position', 'Wakil Departemen');
                                $staffs = $deptMembers->whereNotIn('position', ['Ketua Departemen', 'Wakil Departemen']);
                            @endphp
                            <div class="dept-branch">
                                <div class="dept-title-box">DEPARTEMEN {{ strtoupper($dept->abbreviation ?: $dept->slug) }}</div>
                                
                                @if($kadep)
                                <div class="org-node">
                                    <div class="org-photo">
                                        @if($kadep->photo)
                                            <img src="{{ Storage::url($kadep->photo) }}" alt="Kadep">
                                        @else
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </div>
                                    <div class="org-info">
                                        <h4>{{ $kadep->name }}</h4>
                                        <span>Kadep</span>
                                    </div>
                                </div>
                                @endif

                                @if($wakadep)
                                <div class="org-node">
                                    <div class="org-photo">
                                        @if($wakadep->photo)
                                            <img src="{{ Storage::url($wakadep->photo) }}" alt="Wakadep">
                                        @else
                                            <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a5 5 0 100 10 5 5 0 000-10zm-7 14a7 7 0 1114 0H5z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </div>
                                    <div class="org-info">
                                        <h4>{{ $wakadep->name }}</h4>
                                        <span>Wakadep</span>
                                    </div>
                                </div>
                                @endif

                                @if($staffs->count() > 0)
                                <div class="staff-list">
                                    @foreach($staffs as $staff)
                                        <div class="staff-node">
                                            <h4>{{ $staff->name }}</h4>
                                            <span>{{ $staff->position }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .org-page {
        padding: 60px 0 80px; /* Dikurangi padding topnya agar judul lebih naik ke atas */
        background: var(--blue-pale);
        min-height: 100vh;
        overflow-x: hidden;
    }
    body.dark .org-page { background: #0b1120; }

    .org-tree-wrapper {
        width: 100%;
        overflow-x: auto; /* Untuk scroll di HP */
        padding-bottom: 60px;
        padding-left: 25px; /* Sesuai request: 25px */
        padding-right: 25px;
    }

    .org-tree {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: fit-content; /* Biarkan dia merenggang otomatis mengikuti lebar deretan departemen */
        padding-top: 20px;
        margin: 0 auto;
    }

    /* Common Node Style */
    .org-node {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 16px;
        width: 220px;
        height: 180px; /* Tambahkan tinggi tetap agar semua card seragam */
        text-align: center;
        box-shadow: 0 10px 25px rgba(11,37,69,0.08);
        position: relative;
        z-index: 2;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }
    body.dark .org-node { background: var(--navy); border-color: rgba(255,255,255,0.1); }
    
    .org-node.sub-node {
        width: 180px;
        height: 160px; /* Tinggi disesuaikan untuk card anak */
        padding: 12px;
        margin-top: 20px;
    }

    .org-photo {
        width: 80px;
        height: 80px;
        border-radius: 8px; /* Kotak dengan sudut melengkung sedikit */
        overflow: hidden;
        margin-bottom: 12px;
        background: var(--blue-pale);
        border: 2px solid var(--blue-light);
    }
    body.dark .org-photo { background: #1e293b; border-color: #334155; }
    .org-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .org-photo svg { padding: 15px; color: #94a3b8; }

    .org-info h4 {
        font-family: 'Sora', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 4px;
        line-height: 1.3;
    }
    body.dark .org-info h4 { color: #fff; }
    .org-info span {
        font-size: 0.75rem;
        color: var(--blue);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Lines System using pseudo-elements */
    .org-level {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
        margin-bottom: 40px; /* Jarak vertikal antar level */
    }

    /* Garis Lurus Ke Bawah (Pembina -> Ketum -> Waketum) */
    .level-pembina::after,
    .level-ketum::after {
        content: '';
        position: absolute;
        bottom: -40px;
        left: 50%;
        width: 2px;
        height: 40px;
        background: var(--blue-light);
        transform: translateX(-50%);
        z-index: 1;
    }

    /* LEVEL 4: SEKRETARIS & BENDAHARA */
    .level-sekben {
        margin-top: 40px;
        position: relative;
    }
    /* Garis konektor atas (horizontal) dari Waketum ke Sekben */
    .level-sekben::before {
        content: '';
        position: absolute;
        top: -40px;
        left: 50%;
        width: 2px;
        height: 40px;
        background: var(--blue-light);
        transform: translateX(-50%);
        z-index: 1;
    }
    
    /* Garis Lurus Tengah Menembus Sekben menuju Departemen */
    .level-sekben::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: -40px; /* Sampai menyentuh top dari .level-dept */
        left: 50%;
        width: 2px;
        background: var(--blue-light);
        transform: translateX(-50%);
        z-index: 0; /* Di belakang node sekben agar tidak menabrak secara visual */
    }

    .org-branch-group {
        display: flex;
        justify-content: center;
        gap: 200px; /* Jarak antar Sekum dan Bendum */
        position: relative;
    }

    /* Garis horizontal atas pembagi Sekben */
    .org-branch-group::before {
        content: '';
        position: absolute;
        top: -20px;
        left: calc(50% - 210px); /* Adjust to touch the center of nodes */
        right: calc(50% - 210px);
        height: 2px;
        background: var(--blue-light);
        z-index: 1;
    }

    .org-branch {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    /* Tiang turun ke Sekum dan Bendum */
    .org-branch::before {
        content: '';
        position: absolute;
        top: -20px;
        left: 50%;
        width: 2px;
        height: 20px;
        background: var(--blue-light);
        transform: translateX(-50%);
        z-index: 1;
    }

    /* Tiang turun dari Sekum/Bendum ke Sek1/Sek2 */
    .org-branch::after {
        content: '';
        position: absolute;
        top: 150px; /* Mulai dari bawah kotak Sekum/Bendum */
        bottom: 0;
        left: 50%;
        width: 2px;
        background: var(--blue-light);
        transform: translateX(-50%);
        z-index: 1;
    }

    .org-sub-branch {
        position: relative;
        padding-top: 20px;
    }

    /* LEVEL 5: DEPARTEMEN */
    .level-dept {
        margin-top: 60px;
        position: relative;
    }

    /* Garis vertikal penghubung utama dari atas ke Departemen dihapus (diganti oleh sekben::after) */
    .level-dept::before {
        display: none;
    }

    .dept-wrapper {
        display: flex;
        justify-content: center;
        gap: 30px;
        position: relative;
        padding-top: 20px;
    }

    /* Garis horizontal raksasa penghubung antar departemen */
    .dept-wrapper::before {
        content: '';
        position: absolute;
        top: 20px; /* Sejajar dengan padding-top wrapper */
        left: calc(110px); /* Adjust to middle of first node */
        right: calc(110px); /* Adjust to middle of last node */
        height: 2px;
        background: var(--blue-light);
        z-index: 1;
    }

    .dept-branch {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 220px;
    }

    /* Tiang turun ke setiap departemen */
    .dept-branch::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        width: 2px;
        height: 20px; /* Hubungkan garis horizontal ke kotak departemen */
        background: var(--blue-light);
        transform: translateX(-50%);
        z-index: 1;
    }

    .dept-title-box {
        background: var(--navy);
        color: #fff;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        padding: 8px 16px;
        border-radius: 6px;
        margin-top: 20px;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
        text-align: center;
    }
    body.dark .dept-title-box { background: var(--blue); }

    /* Tiang lurus di dalam departemen (Dari Kadep ke bawah) */
    .dept-branch::after {
        content: '';
        position: absolute;
        top: 60px; /* Start below the title box */
        bottom: 0;
        left: 50%;
        width: 2px;
        background: var(--blue-light);
        transform: translateX(-50%);
        z-index: 1;
    }

    .dept-branch .org-node {
        margin-bottom: 20px;
        width: 220px; /* Disamakan dengan org-node utama */
        height: 180px; /* Disamakan dengan org-node utama */
    }
    .dept-branch .org-node:last-child {
        margin-bottom: 0;
    }

    /* Staff List Box */
    .staff-list {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 16px;
        width: 220px; /* Disamakan dengan org-node utama agar rata */
        box-shadow: 0 10px 25px rgba(11,37,69,0.05);
        position: relative;
        z-index: 2;
    }
    body.dark .staff-list { background: var(--navy); border-color: rgba(255,255,255,0.1); }

    .staff-node {
        padding: 8px 0;
        border-bottom: 1px dashed var(--line);
        text-align: center;
    }
    .staff-node:last-child { border-bottom: none; padding-bottom: 0; }
    .staff-node:first-child { padding-top: 0; }
    
    .staff-node h4 {
        font-family: 'Sora', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 2px;
    }
    body.dark .staff-node h4 { color: #fff; }
    .staff-node span {
        font-size: 0.7rem;
        color: var(--ink-soft);
    }

</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Otomatis men-scroll wrapper ke tengah saat halaman selesai dimuat
        const wrapper = document.querySelector('.org-tree-wrapper');
        const tree = document.querySelector('.org-tree');
        
        if (wrapper && tree) {
            // Hitung sisa ruang yang bisa di-scroll (Lebar Pohon dikurangi Lebar Layar)
            const scrollLeft = (tree.scrollWidth - wrapper.clientWidth) / 2;
            if (scrollLeft > 0) {
                wrapper.scrollLeft = scrollLeft;
            }
        }
    });
</script>
@endpush