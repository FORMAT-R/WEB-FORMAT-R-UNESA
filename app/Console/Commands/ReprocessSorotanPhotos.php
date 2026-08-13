<?php

namespace App\Console\Commands;

use App\Jobs\ProcessMemberPhotoBackground;
use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ReprocessSorotanPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sorotan:reprocess
                            {--department= : Hanya proses member dari department_id tertentu}
                            {--member=     : Hanya proses member dengan id tertentu}
                            {--dry-run     : Tampilkan daftar tanpa benar-benar memproses}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proses ulang (remove background + auto crop) foto sorotan semua pengurus yang sudah ada. Berguna untuk standarisasi foto lama.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $query = Member::whereNotNull('photo_sorotan');

        if ($this->option('department')) {
            $query->where('department_id', $this->option('department'));
        }

        if ($this->option('member')) {
            $query->where('id', $this->option('member'));
        }

        $members = $query->get();

        if ($members->isEmpty()) {
            $this->warn('Tidak ada member dengan foto sorotan yang ditemukan.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$members->count()} member dengan foto sorotan.");

        if ($this->option('dry-run')) {
            $this->table(
                ['ID', 'Nama', 'Department ID', 'Foto Sorotan'],
                $members->map(fn($m) => [
                    $m->id,
                    $m->name,
                    $m->department_id,
                    $m->photo_sorotan,
                ])->toArray()
            );
            $this->line('--dry-run: tidak ada yang diproses.');
            return self::SUCCESS;
        }

        if (!$this->confirm("Lanjutkan memproses ulang {$members->count()} foto sorotan? (foto lama akan diganti)")) {
            $this->line('Dibatalkan.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($members->count());
        $bar->start();

        foreach ($members as $member) {
            // Foto lama tidak di-crop wajah oleh browser → gunakan mode fallback (faceCropped=false)
            // Ini akan tetap menggunakan logika standarisasi rasio 1:1.3 di backend
            ProcessMemberPhotoBackground::dispatch($member->id, false);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Semua job telah di-dispatch ke queue. Pastikan queue worker sedang berjalan.');
        $this->line('Jalankan: php artisan queue:work');

        return self::SUCCESS;
    }
}
