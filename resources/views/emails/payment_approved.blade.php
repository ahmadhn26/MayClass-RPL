<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <style>
        /* Base Reset */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            line-height: 1.6;
            width: 100% !important;
            height: 100% !important;
        }

        /* Layout Helpers */
        .wrapper {
            width: 100%;
            background-color: #f3f4f6;
            padding: 40px 0;
        }

        .container {
            display: block;
            margin: 0 auto !important;
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }

        /* HEADER SECTION (REDESIGNED) */
        .header {
            background-color: #1b6d4f;
            padding: 40px 20px 30px 20px; /* Padding ditambah agar lebih lega */
            text-align: center;
        }

        .icon-circle {
            display: inline-block;
            width: 64px;
            height: 64px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin-bottom: 20px;
            text-align: center;
            line-height: 64px;
        }

        .check-icon {
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
        }

        .header-title {
            color: #ffffff;
            font-size: 26px; /* Sedikit diperbesar */
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
            display: block;
            width: 100%;
            text-align: center;
        }

        .header-subtitle {
            color: #d1fae5;
            font-size: 14px;
            margin-top: 8px;
            font-weight: 500;
        }

        /* Content Section */
        .content {
            padding: 40px;
            text-align: center;
        }

        .greeting {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Receipt Box */
        .receipt-box {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0 24px;
            margin-bottom: 30px;
            text-align: left;
        }

        .receipt-row {
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .receipt-row:last-child {
            border-bottom: none;
        }

        .label {
            font-size: 13px;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .value {
            font-size: 15px;
            color: #111827;
            font-weight: 600;
            float: right;
        }

        .status-badge {
            background-color: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Button */
        .btn {
            display: inline-block;
            background-color: #1b6d4f;
            color: #ffffff !important;
            font-size: 16px;
            font-weight: 600;
            padding: 14px 36px;
            border-radius: 8px;
            text-decoration: none;
            transition: opacity 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(27, 109, 79, 0.2); /* Sedikit bayangan pada tombol */
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background-color: #f9fafb;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #f3f4f6;
        }

        .footer-text {
            font-size: 12px;
            color: #9ca3af;
            margin: 4px 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">

            <div class="header">
                <div class="icon-circle">
                    <span class="check-icon">&#10003;</span>
                </div>
                
                <h1 class="header-title">PEMBAYARAN BERHASIL</h1>
                <p class="header-subtitle">Terima kasih, transaksi Anda telah dikonfirmasi.</p>
            </div>

            <div class="content">
                <p class="greeting">
                    Halo <strong>{{ $order->user->name }}</strong>,<br>
                    Hore! Pembayaran Anda berhasil kami terima. Paket belajar Anda sudah aktif dan siap digunakan sekarang juga.
                </p>

                <div class="receipt-box">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="receipt-row">
                                <span class="label">Status</span>
                                <span style="float: right;" class="status-badge">Lunas</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="receipt-row">
                                <span class="label">Paket</span>
                                <span class="value">{{ $order->package->detail_title }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="receipt-row">
                                <span class="label">Total</span>
                                <span class="value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="receipt-row">
                                <span class="label">Tanggal</span>
                                <span class="value">
                                    {{ $order->paid_at ? $order->paid_at->format('d M Y') : date('d M Y') }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <a href="{{ route('login') }}" class="btn">Mulai Belajar Sekarang</a>
            </div>

            <div class="footer">
                <p class="footer-text">&copy; {{ date('Y') }} MayClass. All rights reserved.</p>
                <p class="footer-text">Email ini dikirim secara otomatis, mohon tidak membalas.</p>
            </div>
        </div>
    </div>
</body>

</html>