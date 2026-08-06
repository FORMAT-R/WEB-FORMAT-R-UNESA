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

        // Check EXIF data to fix rotation before any processing
        if (in_array($extension, ['jpg', 'jpeg', 'tiff'])) {
            $exif = @exif_read_data($path);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $image = imagerotate($image, 180, 0);
                        break;
                    case 6:
                        $image = imagerotate($image, -90, 0);
                        break;
                    case 8:
                        $image = imagerotate($image, 90, 0);
                        break;
                }
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

    /**
     * Remove background from an image using python rembg OR Remove.bg API, convert to WebP, and return the path.
     *
     * @param string $sourcePath Path of the local original file relative to storage/app/public
     * @param string $directory Target directory relative to storage/app/public
     * @return string|null Path to the saved image without background, or null on failure
     */
    public function removeBackgroundAndSaveWebp(string $sourcePath, string $directory): ?string
    {
        // Path absolut sumber
        $absSource = Storage::disk('public')->path($sourcePath);
        if (!file_exists($absSource)) {
            return null;
        }

        $filename = Str::random(40) . '_nobg.webp';
        $storePath = trim($directory, '/') . '/' . $filename;
        $absTarget = Storage::disk('public')->path($storePath);

        // Pastikan folder target ada
        $targetDir = dirname($absTarget);
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Simpan sementara sebagai PNG
        $tempPngPath = sys_get_temp_dir() . '/' . Str::random(20) . '_temp.png';

        // --- Coba gunakan Multi-Key Remove.bg API ---
        $keys = [
            env('REMOVE_BG_KEY_1'),
            env('REMOVE_BG_KEY_2'),
            env('REMOVE_BG_KEY_3'),
        ];
        
        $keys = array_filter($keys); // Hapus key yang kosong
        $apiSuccess = false;

        if (!empty($keys)) {
            foreach ($keys as $key) {
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => "https://api.remove.bg/v1.0/removebg",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => [
                        "X-Api-Key: " . $key
                    ],
                    CURLOPT_POSTFIELDS => [
                        'image_file' => new \CURLFile($absSource),
                        'size' => 'auto'
                    ],
                ]);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode == 200) {
                    file_put_contents($tempPngPath, $response);
                    $apiSuccess = true;
                    \Log::info("Remove.bg API success using a valid key.");
                    break; // Berhenti mencari key jika sudah berhasil
                } else {
                    \Log::warning("Remove.bg API failed for a key. HTTP Code: $httpCode. Message: " . substr($response, 0, 100));
                    // Jika 402/403 (kredit habis/invalid), loop akan lanjut ke key berikutnya
                }
            }
        }

        // --- Fallback ke Python Rembg Lokal jika API tidak disetel atau semua key gagal ---
        if (!$apiSuccess) {
            \Log::info("Falling back to local Python rembg...");
            $command = "rembg i " . escapeshellarg($absSource) . " " . escapeshellarg($tempPngPath) . " 2>&1";
            set_time_limit(1800); // 30 menit
            exec($command, $output, $returnVar);

            if ($returnVar !== 0 || !file_exists($tempPngPath)) {
                \Log::error("Local Rembg failed: " . implode("\n", $output));
                return null;
            }
        }

        // Convert the temporary PNG to WebP with transparency
        $image = @imagecreatefrompng($tempPngPath);
        if ($image) {
            imagepalettetotruecolor($image);
            imagealphablending($image, false); // false agar transparansi terjaga di GD saat disave ke webp
            imagesavealpha($image, true);
            
            // Auto Crop (Trim) ruang kosong (transparan)
            $croppedImage = $this->autoCropTransparent($image);
            if ($croppedImage) {
                imagedestroy($image);
                $image = $croppedImage;
            }

            // Konversi ke webp di memori
            ob_start();
            imagewebp($image, null, 80);
            $imageContent = ob_get_clean();
            imagedestroy($image);
            
            @unlink($tempPngPath);

            if ($imageContent) {
                Storage::disk('public')->put($storePath, $imageContent);
                return $storePath;
            }
        }
        
        @unlink($tempPngPath);
        return null;
    }

    /**
     * Auto crop an image to remove transparent boundaries.
     * 
     * @param \GdImage $image
     * @return \GdImage|false Cropped image resource or false on failure
     */
    private function autoCropTransparent($image)
    {
        $width = imagesx($image);
        $height = imagesy($image);

        $top = 0;
        $bottom = 0;
        $left = 0;
        $right = 0;

        // Cari batas atas
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                if ($alpha < 127) { // 127 berarti transparan penuh
                    $top = $y;
                    break 2;
                }
            }
        }

        // Cari batas bawah
        for ($y = $height - 1; $y >= 0; $y--) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                if ($alpha < 127) {
                    $bottom = $y;
                    break 2;
                }
            }
        }

        // Cari batas kiri
        for ($x = 0; $x < $width; $x++) {
            for ($y = $top; $y <= $bottom; $y++) {
                $color = imagecolorat($image, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                if ($alpha < 127) {
                    $left = $x;
                    break 2;
                }
            }
        }

        // Cari batas kanan
        for ($x = $width - 1; $x >= 0; $x--) {
            for ($y = $top; $y <= $bottom; $y++) {
                $color = imagecolorat($image, $x, $y);
                $alpha = ($color >> 24) & 0x7F;
                if ($alpha < 127) {
                    $right = $x;
                    break 2;
                }
            }
        }

        $newWidth = $right - $left + 1;
        $newHeight = $bottom - $top + 1;

        if ($newWidth <= 0 || $newHeight <= 0) {
            return false;
        }

        // --- STANDARISASI RASIO UNTUK MEMBUANG KAKI (FULL BODY) ---
        // Rasio wajar pas-foto setengah badan biasanya tinggi = lebar * 1.5. 
        // Jika tinggi jauh melebihi itu, berarti ini kemungkinan foto seluruh badan.
        // Kita akan paksa batas bawahnya naik (memotong bagian pinggang ke bawah).
        $idealHeight = (int)($newWidth * 1.5);
        if ($newHeight > $idealHeight) {
            // Karena fokus kita mempertahankan kepala (top), kita potong sisa tinggi dari bawah
            $newHeight = $idealHeight; 
        }

        // Lakukan crop
        $croppedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Pertahankan transparansi
        imagealphablending($croppedImage, false);
        imagesavealpha($croppedImage, true);
        $transparent = imagecolorallocatealpha($croppedImage, 0, 0, 0, 127);
        imagefill($croppedImage, 0, 0, $transparent);

        imagecopy($croppedImage, $image, 0, 0, $left, $top, $newWidth, $newHeight);

        return $croppedImage;
    }
}
