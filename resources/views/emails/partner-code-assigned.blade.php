<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode Partner Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1>Kode Partner Baru Telah Dibuat</h1>

        <p>Halo <strong>{{ $partner->name }}</strong>,</p>

        <p>Selamat! Kode unik partner Anda telah berhasil dibuat dengan detail sebagai berikut:</p>

        <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Kode Unik:</strong> {{ $partnerCode->unique_code }}</p>
            <p><strong>Persentase Fee:</strong> {{ $partnerCode->fee_percentage }}%</p>
            <p><strong>Persentase Diskon:</strong> {{ $partnerCode->reduction_percentage }}%</p>
            <p><strong>Kuota Klaim:</strong> {{ $partnerCode->claim_quota }}</p>
            <p><strong>Maksimal Klaim per Akun:</strong> {{ $partnerCode->max_claim_per_account }}</p>
            <p><strong>Periode Aktif:</strong> {{ \Carbon\Carbon::parse($partnerCode->use_started_at)->format('d M Y') }} - {{ \Carbon\Carbon::parse($partnerCode->use_expired_at)->format('d M Y') }}</p>
        </div>

        <p>Kode ini dapat digunakan oleh pelanggan untuk mendapatkan benefit dari partnership Anda.</p>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}" style="background: #4CAF50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Lihat Dashboard</a>
        </p>

        <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>

        <p>Terima kasih,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
