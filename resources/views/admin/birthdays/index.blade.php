@extends('admin.layouts.app')

@section('title', 'Ulang Tahun - FORMAT-R UNESA')

@section('content')
<div class="space-y-6" x-data="birthdaysData()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ulang Tahun Pengurus</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Daftar ulang tahun pengurus FORMAT-R UNESA.</p>
        </div>
        <a href="{{ route('admin.ultah.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all dark:focus:ring-blue-800 w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Data
        </a>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Toolbar -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" x-model="searchQuery" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl leading-5 bg-white dark:bg-gray-900 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors" placeholder="Cari nama pengurus...">
            </div>
            
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select x-model="filterMonth" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Bulan</option>
                    <option value="Januari">Januari</option>
                    <option value="Februari">Februari</option>
                    <option value="Maret">Maret</option>
                    <option value="April">April</option>
                    <option value="Mei">Mei</option>
                    <option value="Juni">Juni</option>
                    <option value="Juli">Juli</option>
                    <option value="Agustus">Agustus</option>
                    <option value="September">September</option>
                    <option value="Oktober">Oktober</option>
                    <option value="November">November</option>
                    <option value="Desember">Desember</option>
                </select>
                <select x-model="filterDepartment" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Departemen</option>
                    <option value="PSDM">PSDM</option>
                    <option value="KESTARI">KESTARI</option>
                    <option value="MEDKOMINFO">MEDKOMINFO</option>
                    <option value="BPI">BPI</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Pengurus</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Tanggal Lahir</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Pesan Ultah</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse($birthdays as $birthday)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($birthday->photo)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($birthday->photo) }}" alt="{{ $birthday->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                        </div>
                                    @endif
                                    
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $birthday->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $birthday->position }} {{ $birthday->department }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $birthday->birth_date ? \Carbon\Carbon::parse($birthday->birth_date)->translatedFormat('d F Y') : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $birthday->celebration_status === 'sudah_dirayakan' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $birthday->celebration_status === 'sudah_dirayakan' ? 'Sudah Dirayakan' : 'Belum Dirayakan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                  <div class="text-sm text-gray-500 dark:text-gray-400 max-w-[200px] truncate" title="{{ $birthday->message }}">{{ $birthday->message ?: '-' }}</div>
                              </td>
                              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.ultah.edit', $birthday->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-900/30 p-2 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.ultah.destroy', $birthday->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 p-2 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between sm:px-6">
            <!-- (Sama seperti index lain) -->
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Menampilkan <span class="font-medium">{{ count($birthdays) }}</span> hasil
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function birthdaysData() {
    return {
        searchQuery: '',
        filterMonth: '',
        filterDepartment: '',
        birthdays: [
            { id: 1, name: 'Andi Setiawan', department: 'MEDKOMINFO', position: 'Staff', dateStr: '15 Juli 2002', countdown: 'Hari ini! 🎂', status: 'sudah', photo: 'https://ui-avatars.com/api/?name=Andi+Setiawan&background=random', month: 'Juli' },
            { id: 2, name: 'Dewi Lestari', department: 'PSDM', position: 'Kadep', dateStr: '20 Agustus 2001', countdown: 'Dalam 35 hari', status: 'belum', photo: 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=random', month: 'Agustus' }
        ],
        get filteredBirthdays() {
            return this.birthdays.filter(b => {
                const matchesSearch = b.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchesMonth = this.filterMonth === '' || b.month === this.filterMonth;
                const matchesDept = this.filterDepartment === '' || b.department === this.filterDepartment;
                
                return matchesSearch && matchesMonth && matchesDept;
            });
        },
        deleteBirthday(id) {
            if(confirm('Apakah Anda yakin ingin menghapus data ulang tahun ini?')) {
                this.birthdays = this.birthdays.filter(b => b.id !== id);
                const event = new CustomEvent('notify', {
                    detail: { message: 'Data ulang tahun berhasil dihapus!', type: 'success' }
                });
                window.dispatchEvent(event);
            }
        }
    }
}
</script>
@endpush
