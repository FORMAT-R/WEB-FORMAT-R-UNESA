<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageUploadTrait
{
    /**
     * Upload an image, convert to WebP, and return the path.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $quality
     * @return string
     */
    public function uploadImageWebp(UploadedFile $file, string $directory, int $quality = 80): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        // Jika gambar sudah webp, atau format lain yang tidak didukung GD secara default di script ini,
        // simpan secara normal tanpa konversi.
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return $file->store($directory, 'public');
        }

        $image = null;
        if (in_array($extension, ['jpg', 'jpeg'])) {
            $image = @imagecreatefromjpeg($path);
        } elseif ($extension === 'png') {
            $image = @imagecreatefrompng($path);
            if ($image) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
        } elseif ($extension === 'gif') {
            $image = @imagecreatefromgif($path);
            if ($image) {
                imagepalettetotruecolor($image);
            }
        }

        // Jika GD gagal membaca file, gunakan mekanisme upload bawaan
        if (!$image) {
            return $file->store($directory, 'public');
        }

        // Konversi ke webp di memori
        ob_start();
        imagewebp($image, null, $quality);
        $imageContent = ob_get_clean();
        imagedestroy($image);
        
        // Jika gagal konversi memori
        if (!$imageContent) {
            return $file->store($directory, 'public');
        }

        $filename = Str::random(40) . '.webp';
        $storePath = trim($directory, '/') . '/' . $filename;
        
        Storage::disk('public')->put($storePath, $imageContent);

        return $storePath;
    }
}
