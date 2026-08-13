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
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
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
        } elseif ($extension === 'webp') {
            $image = @imagecreatefromwebp($path);
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

        // --- OPTIMASI: RESIZE GAMBAR BESAR SEBELUM KONVERSI KE WEBP ---
        // Jika ukuran gambar terlalu besar (misal dari kamera HP > 1200px lebar/tingginya),
        // kita akan melakukan downscale untuk mempercepat proses kompresi WebP
        // dan menghindari timeout pada proses upload massal.
        $maxWidth = 1200;
        $maxHeight = 1200;
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $newWidth = (int) ($origWidth * $ratio);
            $newHeight = (int) ($origHeight * $ratio);

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Pertahankan transparansi untuk gambar PNG/GIF yang diresize
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
            imagefill($resizedImage, 0, 0, $transparent);

            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($image);
            $image = $resizedImage;
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
     * Simpan foto sorotan dari data base64 (hasil face-crop di browser) sebagai WebP.
     * Digunakan ketika face_cropped=1 dikirim dari form.
     *
     * @param string $base64Data  Data URL base64 (misal: data:image/jpeg;base64,...)
     * @param string $directory   Target directory relative to storage/app/public
     * @return string|null        Path file tersimpan, atau null jika gagal
     */
    public function saveBase64Webp(string $base64Data, string $directory): ?string
    {
        // Pisahkan header dan data
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Data, $matches)) {
            return null;
        }

        $base64 = substr($base64Data, strpos($base64Data, ',') + 1);
        $decoded = base64_decode($base64);
        if (!$decoded) return null;

        // Tulis ke file sementara
        $tempPath = sys_get_temp_dir() . '/' . Str::random(20) . '_facecrp.tmp';
        file_put_contents($tempPath, $decoded);

        // Buka gambar via GD
        $image = @imagecreatefromstring($decoded);
        if (!$image) {
            @unlink($tempPath);
            return null;
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        ob_start();
        imagewebp($image, null, 85);
        $imageContent = ob_get_clean();
        imagedestroy($image);
        @unlink($tempPath);

        if (!$imageContent) return null;

        $filename = Str::random(40) . '_fc.webp';
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
    public function removeBackgroundAndSaveWebp(string $sourcePath, string $directory, bool $faceCropped = false): ?string
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
        
        $localRembgSuccess = false;

        // --- 1. UTAMAKAN MENGGUNAKAN PYTHON REMBG LOKAL ---
        \Log::info("Mencoba menggunakan Python rembg lokal terlebih dahulu...");
        // Lokasi spesifik Python dan script rembg jika Anda ingin memastikannya berjalan dengan conda/venv (opsional)
        // $command = "C:\\path\\to\\python.exe -m rembg i " ... 
        // Atau jika rembg ada di PATH environment:
        $command = "rembg i " . escapeshellarg($absSource) . " " . escapeshellarg($tempPngPath) . " 2>&1";
        
        set_time_limit(1800); // 30 menit
        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($tempPngPath) && filesize($tempPngPath) > 0) {
            \Log::info("Local Rembg berhasil.");
            $localRembgSuccess = true;
        } else {
            \Log::error("Local Rembg gagal: " . implode("\n", $output));
        }

        // --- 2. FALLBACK KE CLIPDROP API (HIGH-RES) JIKA LOKAL GAGAL ---
        $clipdropSuccess = false;
        if (!$localRembgSuccess) {
            \Log::info("Mencoba menggunakan Clipdrop API (High-Res) sebagai fallback pertama...");
            $clipdropKeys = [
                config('services.clipdrop.key_1'),
                config('services.clipdrop.key_2'),
                config('services.clipdrop.key_3'),
            ];
            
            $clipdropKeys = array_filter($clipdropKeys);

            if (!empty($clipdropKeys)) {
                foreach ($clipdropKeys as $key) {
                    $ch = curl_init();
                    curl_setopt_array($ch, [
                        CURLOPT_URL => "https://clipdrop-api.co/remove-background/v1",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_HTTPHEADER => [
                            "x-api-key: " . $key
                        ],
                        CURLOPT_POSTFIELDS => [
                            'image_file' => new \CURLFile($absSource)
                        ],
                    ]);

                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    if ($httpCode == 200) {
                        file_put_contents($tempPngPath, $response);
                        $clipdropSuccess = true;
                        \Log::info("Clipdrop API success using a valid key.");
                        break;
                    } else {
                        \Log::warning("Clipdrop API failed for a key. HTTP Code: $httpCode. Message: " . substr($response, 0, 100));
                    }
                }
            }
        }

        // --- 3. FALLBACK KE REMOVE.BG API (LOW-RES) JIKA CLIPDROP GAGAL ---
        if (!$localRembgSuccess && !$clipdropSuccess) {
            \Log::info("Clipdrop gagal, menggunakan Remove.bg API sebagai fallback terakhir...");
            $keys = [
                config('services.remove_bg.key_1'),
                config('services.remove_bg.key_2'),
                config('services.remove_bg.key_3'),
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
            
            // Jika semuanya gagal
            if (!$apiSuccess) {
                \Log::error("Semua metode remove background gagal (Local, Clipdrop & Remove.bg API).");
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
            // Jika faceCropped=true: JS sudah menghasilkan canvas dengan komposisi wajah
            // yang tepat (600×900). Memanggil autoCropTransparent akan men-trim sisi-sisi
            // dan merusak proporsi tersebut. Cukup skip crop server-side.
            if (!$faceCropped) {
                $croppedImage = $this->autoCropTransparent($image, false);
                if ($croppedImage) {
                    imagedestroy($image);
                    $image = $croppedImage;
                }
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
     * @param bool $faceCropped  Jika true, proporsi sudah distandarkan di browser;
     *                           hanya lakukan trim transparan tanpa ubah rasio.
     * @return \GdImage|false Cropped image resource or false on failure
     */
    private function autoCropTransparent($image, bool $faceCropped = false)
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

        if ($faceCropped) {
            // ── MODE FACE-CROPPED: foto sudah distandarkan di browser ──────
            // Hanya trim area transparan, JANGAN ubah proporsi/rasio.
            // Ini memastikan standarisasi posisi kepala yang dilakukan JS terjaga.
            $paddingTop = (int)($newHeight * 0.02); // padding minimal 2% saja
            $actualTop  = max(0, $top - $paddingTop);
            $paddingAdded = $top - $actualTop;
            $newHeight += $paddingAdded;
        } else {
            // ── MODE FALLBACK: foto belum di-crop JS ─────────────────────────
            // Standarisasi rasio untuk membuang kaki (full body).
            // Jika tinggi > 1.3x lebar, potong dari atas, batasi tinggi 1.3x lebar.
            $idealHeight = (int)($newWidth * 1.3);
            if ($newHeight > $idealHeight) {
                $newHeight = $idealHeight;
            }

            // Padding atas 10% agar tidak terlalu mepet di kepala
            $paddingTop  = (int)($newHeight * 0.1);
            $actualTop   = max(0, $top - $paddingTop);
            $paddingAdded = $top - $actualTop;
            $newHeight   += $paddingAdded;
        }

        // Lakukan crop
        $croppedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Pertahankan transparansi
        imagealphablending($croppedImage, false);
        imagesavealpha($croppedImage, true);
        $transparent = imagecolorallocatealpha($croppedImage, 0, 0, 0, 127);
        imagefill($croppedImage, 0, 0, $transparent);

        imagecopy($croppedImage, $image, 0, 0, $left, $actualTop, $newWidth, $newHeight);

        return $croppedImage;
    }
}
