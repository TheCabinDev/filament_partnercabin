{{-- filepath: resources/views/emails/withdraw-success.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Withdraw Berhasil</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9;">
        <h2 style="color: #2c3e50;">Halo {{ $name }},</h2>

        <p>Penarikan dana Anda telah berhasil dilakukan.</p>

        <div style="background-color: #fff; padding: 20px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">Detail Penarikan Dana:</h3>
            <table style="width: 100%;">
                <tr>
                    <td><strong>Jumlah:</strong></td>
                    <td>Rp {{ number_format($amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Metode:</strong></td>
                    <td>{{ $method }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal:</strong></td>
                    <td>{{ $date_transaction }}</td>
                </tr>
            </table>
        </div>

        <p>Dana akan diproses dalam 1-3 hari kerja.</p>

        <p style="margin-top: 30px;">Terima kasih,<br>Tim {{ config('app.name') }}</p>
    </div>
</body>
</html>
