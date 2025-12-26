<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Berhasil</title>
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

        <div class="header-padding" style="background: linear-gradient(135deg, #4CAF50 0%, #45A049 100%); padding: 40px 30px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 28px; font-weight: 600;">Reservasi Berhasil!</h1>
        </div>

        <div class="content-padding" style="padding: 40px 30px;">
            <p style="font-size: 16px; margin-bottom: 10px;">Halo <strong style="color: #4CAF50;">{{ $name }}</strong>,</p>

            <p style="font-size: 15px; color: #666; margin-bottom: 30px;">Kode partner Anda telah digunakan untuk reservasi yang berhasil! Berikut adalah detail transaksi:</p>

            <div style="background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%); padding: 25px; border-radius: 10px; margin: 25px 0; border: 2px solid #4CAF50;">
                <div style="margin-bottom: 15px;">
                    <span style="color: #666; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Kode Digunakan</span>
                    <div style="font-size: 24px; font-weight: bold; color: #4CAF50; margin-top: 5px; letter-spacing: 2px;">{{ $unique_code }}</div>
                </div>

                <div style="border-top: 1px solid #A5D6A7; margin: 20px 0;"></div>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">ID Reservasi:</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #333; font-size: 14px;">{{ $reservation_id }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Total Harga:</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #333; font-size: 14px;">Rp {{ number_format($total_price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Poin Didapat:</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #4CAF50; font-size: 18px;">Rp {{ number_format($earned_cash, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666; font-size: 14px;">Tanggal Transaksi:</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: bold; color: #333; font-size: 14px;">{{ \Carbon\Carbon::parse($date_transaction)->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>

            <div style="background: #FFF9C4; padding: 20px; border-radius: 8px; border-left: 4px solid #FDD835; margin: 25px 0;">
                <p style="margin: 0; color: #555; font-size: 14px;"><strong>Selamat!</strong> Poin Anda telah bertambah dan siap untuk ditarik.</p>
            </div>

            <div style="text-align: center; margin: 35px 0;">
                <a href="{{ config('app.frontend_url') }}/login" class="button" style="background: linear-gradient(135deg, #4CAF50 0%, #45A049 100%); color: white; padding: 15px 40px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: 600; font-size: 15px; box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);">
                    Lihat Dashboard
                </a>
            </div>

            <p style="font-size: 14px; color: #666; text-align: center; margin-top: 30px;">Terima kasih atas kemitraan Anda bersama kami!</p>
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
