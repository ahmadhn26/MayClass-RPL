<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Diperbarui - MayClass</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #16a34a; padding: 30px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600;">MayClass</h1>
                            <p style="margin: 8px 0 0 0; color: #dcfce7; font-size: 14px;">Bimbingan Belajar Terpercaya
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Greeting -->
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #374151;">
                                Halo <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="margin: 0 0 30px 0; font-size: 16px; color: #374151; line-height: 1.6;">
                                Terdapat perubahan pada jadwal belajar Anda. Mohon perhatikan detail terbaru berikut:
                            </p>

                            <!-- Schedule Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <h2
                                            style="margin: 0 0 20px 0; color: #d97706; font-size: 18px; font-weight: 600; border-bottom: 2px solid #d97706; padding-bottom: 12px;">
                                            JADWAL DIPERBARUI
                                        </h2>

                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td
                                                    style="padding: 8px 0; color: #6b7280; font-size: 14px; width: 140px;">
                                                    Judul</td>
                                                <td
                                                    style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                                    {{ $session->title }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Tanggal
                                                </td>
                                                <td
                                                    style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                                    {{ $session->start_at->translatedFormat('l, d F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Waktu</td>
                                                <td
                                                    style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">
                                                    {{ $session->start_at->format('H:i') }} WIB
                                                    ({{ $session->duration_minutes }} menit)</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Kategori
                                                </td>
                                                <td style="padding: 8px 0; color: #111827; font-size: 14px;">
                                                    {{ $session->category }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Pengajar
                                                </td>
                                                <td style="padding: 8px 0; color: #111827; font-size: 14px;">
                                                    {{ $session->mentor_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Lokasi</td>
                                                <td style="padding: 8px 0; color: #111827; font-size: 14px;">
                                                    {{ $session->location ?? 'Akan diinformasikan' }}</td>
                                            </tr>
                                            @if($session->zoom_link)
                                                <tr>
                                                    <td style="padding: 8px 0; color: #6b7280; font-size: 14px;">Link Kelas
                                                    </td>
                                                    <td style="padding: 8px 0;">
                                                        <a href="{{ $session->zoom_link }}"
                                                            style="color: #16a34a; font-size: 14px; text-decoration: underline;">Klik
                                                            untuk bergabung</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Note -->
                            <p style="margin: 0 0 20px 0; font-size: 14px; color: #6b7280; line-height: 1.6;">
                                Pastikan Anda mencatat perubahan jadwal ini. Jika ada pertanyaan, silakan hubungi admin
                                MayClass.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ config('app.url') }}"
                                            style="display: inline-block; background-color: #16a34a; color: #ffffff; padding: 14px 32px; font-size: 14px; font-weight: 600; text-decoration: none; border-radius: 6px;">
                                            Buka Dashboard MayClass
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px 40px; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #6b7280; text-align: center;">
                                Email ini dikirim secara otomatis oleh sistem MayClass.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #9ca3af; text-align: center;">
                                MayClass - Bimbingan Belajar Terpercaya
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>