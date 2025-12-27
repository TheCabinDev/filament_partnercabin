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
        :root, body, .container {
            color-scheme: only light;
            supported-color-schemes: light;
            background: #fff !important;
        }
        @media (prefers-color-scheme: dark) {
            body, .container {
                background: #fff !important;
                color: #2C3E50 !important;
            }
            .header-padding {
                background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%) !important;
            }
        }
    </style>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; background: #f5f5f5; margin: 0; padding: 30px;">

<div class="container" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(255, 107, 53, 0.3);">

    <div class="header-padding" style="background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%); padding: 50px 30px; text-align: center; position: relative;">
        <h1 style="color: #ffffff; margin: 0; font-size: 32px; font-weight: 800; text-shadow: 0 2px 10px rgba(0,0,0,0.1);">Reservasi Berhasil!</h1>
        <p style="margin-top: 12px; color: #FFE5D9; font-size: 16px; font-weight: 500;">
            Transaksi partner Anda telah berhasil diproses
        </p>
    </div>

    <div class="content-padding" style="padding: 45px 35px;">

        <p style="font-size: 17px; margin-bottom: 10px; color: #2C3E50;">
            Halo <strong style="color: #FF6B35;">{{ $name }}</strong>
        </p>

        <p style="font-size: 15px; color: #5D6D7E; margin-bottom: 35px; line-height: 1.7;">
            Kode partner Anda berhasil digunakan. Berikut detail transaksi reservasi:
        </p>

        <div style="background: linear-gradient(135deg, #FFF5F0 0%, #FFE8DC 100%); padding: 30px; border-radius: 16px; border: 3px solid #FF8C42; margin: 30px 0; box-shadow: 0 4px 15px rgba(255, 107, 53, 0.15);">

            <div style="text-align: center; margin-bottom: 25px;">
                <span style="font-size: 12px; color: #95A5A6; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">
                    Kode Digunakan
                </span>
                <div style="font-size: 32px; font-weight: 900; color: #FF6B35; margin-top: 10px; letter-spacing: 4px; text-shadow: 0 2px 5px rgba(255, 107, 53, 0.2);">
                    {{ $unique_code }}
                </div>
            </div>

            <div style="border-top: 2px dashed #FF8C42; margin: 25px 0;"></div>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 12px 0; color: #5D6D7E; font-size: 15px;">ID Reservasi</td>
                    <td style="padding: 12px 0; text-align: right; font-weight: 700; color: #2C3E50; font-size: 15px;">{{ $reservation_id }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #5D6D7E; font-size: 15px;">Total Harga</td>
                    <td style="padding: 12px 0; text-align: right; font-weight: 700; color: #2C3E50; font-size: 15px;">
                        Rp {{ number_format($total_price, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="background: rgba(255, 107, 53, 0.08); border-radius: 8px;">
                    <td style="padding: 15px 10px; color: #5D6D7E; font-size: 15px; border-radius: 8px 0 0 8px;">Poin Didapat</td>
                    <td style="padding: 15px 10px; text-align: right; font-size: 20px; font-weight: 900; color: #FF6B35; border-radius: 0 8px 8px 0;">
                        Rp {{ number_format($earned_cash, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px 0; color: #5D6D7E; font-size: 15px;">Tanggal Transaksi</td>
                    <td style="padding: 12px 0; text-align: right; font-weight: 700; color: #2C3E50; font-size: 15px;">
                        {{ \Carbon\Carbon::parse($date_transaction)->format('d M Y H:i') }}
                    </td>
                </tr>
            </table>
        </div>

        <div style="background: linear-gradient(135deg, #FFFAF5 0%, #FFF0E5 100%); padding: 25px; border-radius: 12px; border-left: 6px solid #FF6B35; margin: 30px 0; box-shadow: 0 3px 10px rgba(255, 107, 53, 0.1);">
            <p style="margin: 0; color: #34495E; font-size: 15px; line-height: 1.7;">
                <strong style="color: #FF6B35; font-size: 16px;">Selamat!</strong> Poin Anda telah bertambah dan siap untuk ditarik kapan saja.
            </p>
        </div>

        <div style="text-align: center; margin: 40px 0 30px 0;">
            <a href="{{ config('app.frontend_url') }}/login"
               class="button"
               style="background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%); color: white; padding: 18px 50px; text-decoration: none; border-radius: 50px; display: inline-block; font-weight: 800; font-size: 16px; box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4); transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px;">
                Lihat Dashboard
            </a>
        </div>

        <p style="font-size: 14px; color: #95A5A6; text-align: center; margin-top: 35px; font-style: italic;">
            Terima kasih telah menjadi partner kami
        </p>
    </div>

    <div style="background: linear-gradient(135deg, #2C3E50 0%, #34495E 100%); padding: 30px; text-align: center; border-top: 4px solid #FF6B35;">
        <p style="margin: 0; color: #BDC3C7; font-size: 13px; font-weight: 500;">Hormat kami,</p>
        <p style="margin: 10px 0 0 0; color: #ffffff; font-size: 20px; font-weight: 800; letter-spacing: 1px;">
            The Cabin Hotel
        </p>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255, 107, 53, 0.3);">
            <p style="margin: 0; color: #95A5A6; font-size: 12px;">
                © 2024 The Cabin Hotel. All rights reserved.
            </p>
        </div>
    </div>

</div>
</body>
</html>
