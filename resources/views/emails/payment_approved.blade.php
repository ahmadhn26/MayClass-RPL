<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <style>
        /* Reset styles */
        body,
        p,
        h1,
        h2,
        h3,
        div,
        span {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f6f7f8;
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
            color: #14352c;
        }

        .wrapper {
            width: 100%;
            background-color: #f6f7f8;
            padding: 40px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(31, 107, 79, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #3fa67e 0%, #1b6d4f 100%);
            padding: 48px 24px;
            text-align: center;
        }

        .success-icon {
            width: 72px;
            height: 72px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            backdrop-filter: blur(4px);
        }

        .success-checkmark {
            color: #ffffff;
            font-size: 36px;
            line-height: 1;
        }

        .title {
            color: #ffffff;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.92);
            font-size: 16px;
        }

        .content {
            padding: 40px 32px;
        }

        .message {
            color: #4d5660;
            font-size: 16px;
            text-align: center;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .info-card {
            background-color: #f8faf9;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid rgba(63, 166, 126, 0.15);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px dashed rgba(63, 166, 126, 0.2);
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .info-value {
            color: #14352c;
            font-size: 15px;
            font-weight: 600;
            text-align: right;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: rgba(63, 166, 126, 0.1);
            color: #1b6d4f;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-container {
            text-align: center;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(120deg, #3fa67e 0%, #1b6d4f 100%);
            color: #ffffff;
            font-weight: 600;
            padding: 16px 40px;
            border-radius: 99px;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(63, 166, 126, 0.25);
            font-size: 16px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(63, 166, 126, 0.35);
        }

        .footer {
            background-color: #f8faf9;
            padding: 24px;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .footer-text {
            color: #9ca3af;
            font-size: 12px;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header with Success Icon -->
            <div class="header">
                <div class="success-icon">
                    <span class="success-checkmark">✓</span>
                </div>
                <h1 class="title">Pembayaran Berhasil</h1>
                <p class="subtitle">Terima kasih, pembayaran Anda telah dikonfirmasi.</p>
            </div>

            <!-- Main Content -->
            <div class="content">
                <p class="message">
                    Halo <strong>{{ $order->user->name }}</strong>, pembayaran Anda telah kami terima. Sekarang Anda
                    dapat mengakses materi pembelajaran yang Anda pilih.
                </p>

                <!-- Order Details Card -->
                <div class="info-card">
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="status-badge">Lunas / Verified</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Username</span>
                        <span class="info-value">{{ $order->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Paket</span>
                        <span class="info-value">{{ $order->package->detail_title }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Pembayaran</span>
                        <span class="info-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal</span>
                        <span
                            class="info-value">{{ $order->paid_at ? $order->paid_at->format('d M Y H:i') : date('d M Y') }}</span>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="btn-container">
                    <a href="{{ route('login') }}" class="btn">Masuk ke Akun</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p class="footer-text">Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
                <p class="footer-text">&copy; {{ date('Y') }} MayClass. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>