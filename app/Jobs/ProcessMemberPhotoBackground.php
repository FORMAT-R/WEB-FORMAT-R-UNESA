<?php

namespace App\Jobs;

use App\Models\Member;
use App\Traits\ImageUploadTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMemberPhotoBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ImageUploadTrait;

    public $memberId;
    public $timeout = 1800; // Beri waktu 30 menit maksimal untuk AI memotong (menghindari timeout worker)

    /**
     * Create a new job instance.
     */
    public function __construct($memberId)
    {
        $this->memberId = $memberId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $member = Member::find($this->memberId);
        
        // Cek jika member masih ada dan memiliki foto sorotan
        if ($member && $member->photo_sorotan) {
            
            // Proses remove background menggunakan trait
            $nobgPath = $this->removeBackgroundAndSaveWebp($member->photo_sorotan, 'members_sorotan');
            
            if ($nobgPath) {
                // Update tabel jika berhasil
                $member->photo_nobg = $nobgPath;
                $member->save();
            }
        }
    }
}
