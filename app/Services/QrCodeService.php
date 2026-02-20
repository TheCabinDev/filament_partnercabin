<?php

namespace App\Services;

// [\chillerlan\QRCode\QRCode::class]
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\Output\QRGdImageWithLogo;

class QrCodeService
{
    /**
     * Menghasilkan QR Code dalam format Base64 Data URI
     */
    public function generateAndSave(string $uniqueCode): string
    {
        $baseUrl = rtrim(env('APP_URL_FE', 'http://localhost:3000'), '/');
        $fullUrl = $baseUrl . '/?ref=' . $uniqueCode;

        // Pastikan file logo tersedia di path ini
        $logoPath = public_path('logocabin.png');
        $options = new QROptions([
            'version'      => 7, // Versi tinggi agar area data lebih luas
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_H, // Wajib High untuk menutupi bagian tengah
            'scale'        => 10,
            'imageBase64'  => false,
            'addQuietzone' => true,
        ]);
        // 2. Generate QR Code Dasar
        $qrcode = new QRCode($options);
        $qrImageData = $qrcode->render($fullUrl);
        $qrImage = imagecreatefromstring($qrImageData);

        // Aktifkan alpha blending untuk transparansi logo
        imagealphablending($qrImage, true);
        imagesavealpha($qrImage, true);

        $qrWidth  = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);

        // 3. Buat Lingkaran Putih di Tengah
        $circleSize = $qrWidth * 0.28; // Ukuran lingkaran (28% dari lebar QR)
        $centerX    = $qrWidth / 2;
        $centerY    = $qrHeight / 2;

        $bgColor = imagecolorallocate($qrImage, 255, 255, 255);

        // Menggambar lingkaran putih solid
        imagefilledellipse($qrImage, $centerX, $centerY, $circleSize, $circleSize, $bgColor);

        // 4. Proses Logo
        if (file_exists($logoPath)) {
            $logoImage = imagecreatefrompng($logoPath);
            $logoW     = imagesx($logoImage);
            $logoH     = imagesy($logoImage);

            // Ukuran logo di dalam lingkaran (dibuat lebih kecil sedikit dari lingkaran)
            $logoQrWidth  = $circleSize * 0.7;
            $scale        = $logoW / $logoQrWidth;
            $logoQrHeight = $logoH / $scale;

            $fromX = ($qrWidth - $logoQrWidth) / 2;
            $fromY = ($qrHeight - $logoQrHeight) / 2;

            // Tempelkan logo di atas lingkaran putih
            imagecopyresampled(
                $qrImage,
                $logoImage,
                (int)$fromX,
                (int)$fromY,
                0,
                0,
                (int)$logoQrWidth,
                (int)$logoQrHeight,
                $logoW,
                $logoH
            );

            imagedestroy($logoImage);
        }

        // 5. Simpan Hasil Akhir
        $fileName = 'partners/QRcode/' . $uniqueCode . '.png';
        ob_start();
        imagepng($qrImage);
        $finalImage = ob_get_clean();

        Storage::disk('public')->put($fileName, $finalImage);

        // Cleanup memori
        imagedestroy($qrImage);
        imagedestroy($logoImage);

        return $fileName;
    }
}
