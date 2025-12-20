<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Partner Baru</title>
    <style>
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 10px !important;
            }
            .content-padding {
                padding: 25px 20px !important;
            }
            .header-padding {
                padding: 30px 20px !important;
            }
            .code-text {
                font-size: 20px !important;
            }
            .button {
                padding: 12px 30px !important;
                font-size: 14px !important;
            }
            table td {
                font-size: 13px !important;
            }
        }
    </style>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; margin: 0; padding: 20px;">
    <div class="container" style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

        <div class="header-padding" style="background: linear-gradient(135deg, #FF8C42 0%, #FF6B35 100%); padding: 40px 30px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 600;">Kode Partner Berhasil Dibuat!</h1>
        </div>

        <div class="content-padding" style="padding: 40px 30px;">
            <p style="font-size: 16px; margin-bottom: 10px;">Halo <strong style="color: #FF6B35;">{{ $partner->name }}</strong>,</p>

            <p style="font-size: 15px; color: #666; margin-bottom: 30px;">Selamat! Kode unik partner Anda telah berhasil dibuat. Berikut adalah detail kode partnership Anda:</p>

            <div style="background: linear-gradient(135deg, #FFF4E6 0%, #FFE8D6 100%); padding: 25px; border-radius: 10px; margin: 25px 0; border: 2px solid #FF8C42;">
                <div style="margin-bottom: 15px;">
                    <span style="color: #666; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Kode Unik</span>
                    <div class="code-text" style="font-size: 24px; font-weight: bold; color: #FF6B35; margin-top: 5px; letter-spacing: 2px; word-break: break-all;">{{ $partnerCode->unique_code }}</div>
                </div>

                <div style="border-top: 1px solid #FFD4B8; margin: 20px 0;"></div>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Persentase Fee:</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #FF6B35; font-size: 14px;">{{ $partnerCode->fee_percentage }}%</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Persentase Diskon:</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #FF6B35; font-size: 14px;">{{ $partnerCode->reduction_percentage }}%</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Periode Aktif:</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #333; font-size: 13px;">{{ \Carbon\Carbon::parse($partnerCode->use_started_at)->format('d M Y') }} - {{ \Carbon\Carbon::parse($partnerCode->use_expired_at)->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>

            <div style="background: #F8F9FA; padding: 20px; border-radius: 8px; border-left: 4px solid #FF8C42; margin: 25px 0;">
                <p style="margin: 0; color: #555; font-size: 14px;"><strong>Tips:</strong> Bagikan kode ini kepada pelanggan Anda untuk mendapatkan benefit dari partnership yang telah dibuat.</p>
            </div>

            <div style="text-align: center; margin: 35px 0;">
                <a href="{{ config('app.frontend_url') }}/login" class="button" style="background: linear-gradient(135deg, #FF8C42 0%, #FF6B35 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: 600; font-size: 15px; box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);">
                    Lihat Dashboard
                </a>
            </div>

            <p style="font-size: 14px; color: #666; text-align: center; margin-top: 30px;">Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
        </div>

        <div class="content-padding" style="background: #2C2C2C; padding: 25px 30px; text-align: center;">
            <p style="margin: 0; color: #999; font-size: 13px;">Terima kasih,</p>
            <p style="margin: 5px 0 0 0; color: white; font-size: 16px; font-weight: 600;">The Cabin Hotel</p>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #444;">
                <p style="margin: 0; color: #777; font-size: 12px;">© 2024 The Cabin Hotel. All rights reserved.</p>
            </div>
        </div>

    </div>
</body>
</html>
