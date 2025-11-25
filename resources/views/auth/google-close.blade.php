<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <title>MayClass - Menutup Jendela...</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <style>
            body {
                margin: 0;
                font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                background: #f1f5f9;
                color: #0f172a;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }

            .message {
                text-align: center;
                padding: 24px;
            }

            .message h1 {
                font-size: 1.25rem;
                margin-bottom: 8px;
            }

            .message p {
                margin: 0;
                font-size: 0.95rem;
                color: #475569;
            }
        </style>
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const target = @json($redirectUrl);

                try {
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.href = target;
                        window.opener.focus();
                        window.close();
                        return;
                    }
                } catch (error) {
                    // Cross-origin access could throw; fallback to full redirect
                }

                window.location.href = target;
            });
        </script>
    </head>
    <body>
        <div class="message">
            <h1>Mengarahkan ke MayClass...</h1>
            <p>Jika jendela ini tidak tertutup otomatis, tutup secara manual dan kembali ke MayClass.</p>
        </div>
    </body>
</html>
