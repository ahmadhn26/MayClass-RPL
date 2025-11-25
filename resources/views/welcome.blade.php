<!DOCTYPE html>
<html lang="id" data-page="landing">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>MayClass - Bimbingan Belajar Premium untuk Raih Prestasi</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />
        <style>
            :root {
                color-scheme: light;
                --nav-height: 64px;
                --primary-dark: #1b6d4f;
                --primary-main: #3fa67e;
                --primary-light: #84d986;
                --primary-accent: #a8e6a1;
                --neutral-900: #1f2328;
                --neutral-700: #4d5660;
                --neutral-100: #f6f7f8;
                --surface: #ffffff;
                --ink-strong: #14352c;
                --ink-muted: rgba(20, 59, 46, 0.78);
                --ink-soft: rgba(20, 59, 46, 0.62);
                --nav-surface: linear-gradient(135deg, rgba(80, 190, 150, 0.98), rgba(63, 166, 126, 0.98));
                --footer-bg: #0d261f; /* Warna background footer baru (Deep Teal) */
                --footer-text: #a3b3ad;
                --footer-heading: #ffffff;
                --shadow-lg: 0 24px 60px rgba(31, 107, 79, 0.2);
                --shadow-md: 0 18px 40px rgba(31, 107, 79, 0.12);
                --radius-lg: 20px;
                --radius-xl: 28px;
            }
            *,
            *::before,
            *::after {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                margin: 0;
                font-family: "Poppins", sans-serif;
                color: var(--ink-strong);
                background: #ffffff;
                line-height: 1.7;
            }

            img {
                display: block;
                max-width: 100%;
                height: auto;
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            /* Full-width layout with proper container centering */
            .container {
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 32px;
            }

            /* Ensure all sections have consistent full-width layout */
            .section {
                width: 100%;
                padding: 96px 0;
            }

            .section .container {
                width: 100%;
            }

            [data-reveal] {
                opacity: 0;
                transform: translateY(48px);
                transition: opacity 700ms cubic-bezier(0.16, 1, 0.3, 1),
                    transform 700ms cubic-bezier(0.16, 1, 0.3, 1);
                transition-delay: var(--reveal-delay, 0ms);
                will-change: opacity, transform;
            }

            [data-reveal].is-visible {
                opacity: 1;
                transform: translateY(0);
            }

            /* ==== NAVBAR ==== */
            header {
                overflow: visible;
                padding-top: 0;
            }

            nav {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                width: 100%;
                /* Padding diperkecil sedikit agar navbar tidak terlalu tinggi saat logo membesar */
                padding: 8px clamp(12px, 3vw, 24px);
                
                /* Glassmorphism putih */
                background: rgba(255, 254, 254, 0.52); 
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                border-bottom: 1px solid rgba(255, 255, 255, 0.25);
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);

                transition: background 0.3s ease, box-shadow 0.3s ease;
            }

            .nav-inner {
                display: grid;
                grid-template-columns: auto 1fr auto; /* kiri - tengah - kanan */
                align-items: center;
                width: 100%;
                padding: 0 32px;
                gap: 20px;
            }

            @media (max-width: 1024px) {
                nav {
                    padding: 16px clamp(8px, 4vw, 20px);
                }

                .nav-inner {
                    gap: clamp(18px, 4vw, 32px);
                }
            }

            @media (max-width: 768px) {
                nav {
                    padding: 16px clamp(8px, 6vw, 16px);
                    margin-bottom: 24px;
                }

                .nav-inner {
                    grid-template-columns: 1fr;
                    justify-items: center;
                    gap: 18px;
                }

                .nav-inner > * {
                    width: 100%;
                    justify-self: center;
                }
            }

            .brand {
                display: flex;
                align-items: center;
                gap: 14px;
                font-weight: 600;
                font-size: 1.25rem;
                flex-shrink: 0;
                justify-self: start;
            }

            /* REVISI LOGO: Diperbesar heightnya, width auto */
            .brand img {
                height: 90px; 
                width: auto;
                object-fit: contain;
            }

            .nav-links {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 28px;
                font-size: 0.95rem;
                margin-left: 57px;
            }

            .nav-links a {
                color: #000;
                transition: color 0.2s ease;
            }

            .nav-links a:hover {
                color: var(--primary-main);
            }

            .nav-actions {
                display: flex;
                align-items: center;
                gap: 16px;
                justify-content: flex-end;
                justify-self: end;
                color: #000
            }

            .nav-profile {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                border: 2px solid rgba(255, 255, 255, 0.6);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                background: rgba(255, 255, 255, 0.2);
                box-shadow: 0 4px 16px rgba(15, 23, 42, 0.18);
            }

            .nav-profile img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

            @media (max-width: 768px) {
                .brand img {
                    height: 42px; /* Ukuran mobile sedikit lebih kecil agar tidak overflow */
                }
                .nav-actions {
                    width: 100%;
                    justify-content: center;
                }
                
                .nav-links {
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 16px;
                    margin-left: 0;
                }
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 12px 28px;
                border-radius: 999px;
                font-size: 0.95rem;
                font-weight: 500;
                border: 1px solid transparent;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                cursor: pointer;
            }

            .btn-outline {
                border-color: rgba(255, 255, 255, 0.38);
                color: #ffffff;
                background: transparent;
            }

            .btn-outline:hover {
                transform: translateY(-1px);
                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
            }

            .btn-primary {
                background: linear-gradient(120deg, var(--primary-light) 0%, var(--primary-accent) 100%);
                color: var(--primary-dark);
                box-shadow: 0 16px 40px rgba(132, 217, 134, 0.36);
            }

            .btn-primary:hover {
                transform: translateY(-1px);
            }

            .btn-ghost {
                border-color: rgba(63, 166, 126, 0.35);
                background: rgba(63, 166, 126, 0.08);
                color: var(--primary-dark);
            }

            .btn-ghost:hover {
                transform: translateY(-1px);
                box-shadow: 0 14px 32px rgba(63, 166, 126, 0.2);
            }

            .hero {
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center; 
                text-align: center;
                width: 100%;
                height: 900px;
                min-height: calc(100vh + 120px);
                padding-top: calc(var(--nav-height) + 60px);
                padding-bottom: 100px;
                background: 
                    linear-gradient(
                        to bottom,
                        rgba(0, 0, 0, 0.35),
                        rgba(0, 0, 0, 0.65)
                    ),
                    url('/images/stis_contoh.jpeg') center/cover no-repeat;
            }

            .hero-content {
                max-width: 800px;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .hero h1 {
                font-size: clamp(2.7rem, 4vw, 3.9rem);
                line-height: 1.15;
                margin: 18px 0;
                color: #ffffff;
                text-align: center;
                max-width: 720px;
            }

            .hero p {
                color: rgba(255, 255, 255, 0.94);
                margin: 0 0 32px;
                max-width: 640px;
                text-align: center;
                font-size: 1.1rem;
            }

            .badge {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 10px 18px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.16);
                font-weight: 600;
                letter-spacing: 0.02em;
                color: #ffffff;
            }

            .badge-soft {
                background: rgba(20, 59, 46, 0.08);
                color: var(--primary-dark);
                border: 1px solid rgba(20, 59, 46, 0.08);
            }

            .hero-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 16px;
                margin-bottom: 40px;
            }

            .section-header {
                max-width: 760px;
                margin: 0 auto 64px;
                text-align: center;
                display: grid;
                gap: 16px;
            }

            .section-header h2 {
                margin: 0;
                font-size: clamp(2rem, 3vw, 2.7rem);
                color: var(--ink-strong);
            }

            .section-header p {
                margin: 0;
                color: var(--ink-muted);
            }

            /* Articles grid in full-width container */
            .articles-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 28px;
                width: 100%;
            }

            .article-card {
                background: var(--surface);
                border-radius: var(--radius-xl);
                box-shadow: var(--shadow-md);
                overflow: hidden;
                display: grid;
                grid-template-rows: 220px 1fr;
            }

            .article-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .article-content {
                padding: 24px 26px 32px;
                display: grid;
                gap: 10px;
            }

            .article-content h3 {
                margin: 0;
                font-size: 1.15rem;
                color: var(--ink-strong);
            }

            .article-content p {
                margin: 0;
                color: var(--ink-soft);
                font-size: 0.95rem;
            }

            .link-muted {
                color: var(--primary-main);
                font-weight: 600;
            }

            /* Full-width pricing section */
            .pricing-section {
                padding: 64px 0;
                background: var(--neutral-100);
            }

            .pricing-section .section-header {
                max-width: 720px;
                margin: 0 auto 32px;
                text-align: center;
            }

            .pricing-section .section-title {
                margin: 0 0 8px;
                font-size: 2.5rem;
            }

            .pricing-section .section-subtitle {
                margin: 0;
                color: var(--ink-soft);
                font-size: 0.98rem;
            }

            .pricing-group {
                margin-top: 32px;
                display: grid;
                gap: 18px;
            }

            .pricing-group > div:first-child {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .pricing-group > div:first-child h3 {
                margin: 0;
                font-size: 1.2rem;
            }

            .pricing-group > div:first-child p {
                margin: 0;
                color: var(--ink-soft);
                font-size: 0.92rem;
            }

            .pricing-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 20px;
            }

            .pricing-card {
                position: relative;
                display: grid;
                gap: 14px;
                padding: 20px 18px 18px;
                border-radius: 18px;
                background: var(--surface);
                border: 1px solid var(--neutral-100);
                box-shadow: 0 16px 30px rgba(15, 23, 42, 0.06);
                transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            }

            .pricing-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 20px 38px rgba(15, 23, 42, 0.08);
                border-color: rgba(63, 166, 126, 0.28);
            }

            .pricing-card .badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 6px 12px;
                border-radius: 999px;
                font-size: 0.78rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                background: rgba(63, 166, 126, 0.12);
                color: var(--primary-main);
            }

            .pricing-card strong {
                font-size: 1.1rem;
            }

            .pricing-price {
                font-size: 1.4rem;
                font-weight: 700;
                color: var(--primary-dark);
            }

            .pricing-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                font-size: 0.9rem;
                color: var(--ink-soft);
            }

            .pricing-features {
                list-style: none;
                padding: 0;
                margin: 6px 0 0;
                display: grid;
                gap: 6px;
                font-size: 0.9rem;
                color: var(--ink-soft);
            }

            .pricing-features li::before {
                content: '•';
                margin-right: 6px;
                color: var(--primary-main);
                font-weight: 700;
            }

            .pricing-actions {
                margin-top: 6px;
                display: flex;
                gap: 10px;
                align-items: center;
                justify-content: flex-start;
            }

            .pricing-actions .btn {
                font-size: 0.92rem;
                border-radius: 999px;
                padding: 10px 18px;
                text-decoration: none;
            }

            .pricing-actions .btn-primary {
                background: var(--primary-main);
                border: none;
                color: #ffffff;
                font-weight: 600;
            }

            .pricing-actions .btn-primary:hover {
                background: var(--primary-dark);
            }

            /* Highlight section */
            .highlight-section {
                position: relative;
                width: 100%;
                background: linear-gradient(110deg, rgba(31, 109, 79, 0.96), rgba(17, 54, 37, 0.92)),
                    url("https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1600&q=80")
                        center/cover;
                color: #ffffff;
                overflow: hidden;
                margin: 0;
                padding: 80px 32px;
            }

            .highlight-content {
                display: grid;
                gap: 48px;
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0;
            }

            .highlight-grid {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 24px;
                width: 100%;
            }

            .highlight-card {
                background: rgba(255, 255, 255, 0.12);
                border-radius: var(--radius-lg);
                padding: 28px;
                backdrop-filter: blur(12px);
                display: grid;
                gap: 12px;
                border: 1px solid rgba(255, 255, 255, 0.16);
            }

            .highlight-card strong {
                color: #ffffff;
            }

            .highlight-card p {
                margin: 0;
            }

            /* Testimonials */
            .testimonials {
                background: radial-gradient(circle at top, rgba(63, 166, 126, 0.1), transparent 55%), #f6fbf8;
                position: relative;
            }

            .testimonials::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(120deg, rgba(63, 166, 126, 0.08), transparent 55%);
                pointer-events: none;
            }

            .testimonials .container {
                position: relative;
                z-index: 1;
            }

            .testimonials-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 24px;
                margin-top: 32px;
            }

            .testimonial-card {
                background: #ffffff;
                border-radius: 28px;
                padding: 32px;
                display: flex;
                flex-direction: column;
                gap: 18px;
                box-shadow: 0 25px 70px rgba(31, 107, 79, 0.12);
                border: 1px solid rgba(20, 59, 46, 0.08);
                min-height: 320px;
            }

            .testimonial-rating {
                display: inline-flex;
                gap: 4px;
                color: #f5b642;
                font-size: 1.1rem;
            }

            .testimonial-quote {
                font-size: 1.05rem;
                font-weight: 500;
                color: var(--ink-strong);
                margin: 0;
                line-height: 1.7;
            }

            .testimonial-quote span {
                color: var(--primary-main);
            }

            .testimonial-author {
                margin-top: auto;
                display: flex;
                align-items: center;
                gap: 14px;
            }

            .testimonial-avatar {
                width: 64px;
                height: 64px;
                border-radius: 20px;
                overflow: hidden;
                border: 2px solid rgba(63, 166, 126, 0.4);
                box-shadow: 0 12px 24px rgba(15, 52, 38, 0.18);
            }

            .testimonial-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .testimonial-meta {
                display: flex;
                flex-direction: column;
                gap: 4px;
                font-size: 0.92rem;
                color: var(--ink-soft);
            }

            .testimonial-meta strong {
                color: var(--ink-strong);
                font-size: 1rem;
            }

            .testimonial-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 4px 10px;
                border-radius: 999px;
                background: rgba(63, 166, 126, 0.12);
                color: var(--primary-dark);
                font-size: 0.8rem;
                font-weight: 600;
            }

            /* Mentor showcase */
            .mentor-showcase {
                background: #fbfdfc;
                border-top: 1px solid rgba(20, 59, 46, 0.06);
            }

            .mentor-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: 24px;
                margin-top: 40px;
            }

            .mentor-profile {
                background: #ffffff;
                border-radius: 24px;
                padding: 28px;
                display: flex;
                flex-direction: column;
                gap: 12px;
                border: 1px solid rgba(20, 59, 46, 0.08);
                box-shadow: 0 16px 48px rgba(15, 52, 38, 0.12);
                position: relative;
                overflow: hidden;
            }

            .mentor-profile::after {
                content: "";
                position: absolute;
                inset: auto -40% -40% 40%;
                height: 160px;
                background: radial-gradient(circle, rgba(63, 166, 126, 0.25), transparent 70%);
                pointer-events: none;
            }

            .mentor-avatar {
                width: 80px;
                height: 80px;
                border-radius: 28px;
                overflow: hidden;
                border: 2px solid rgba(63, 166, 126, 0.35);
                box-shadow: 0 14px 36px rgba(15, 52, 38, 0.15);
            }

            .mentor-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .mentor-info {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .mentor-info strong {
                font-size: 1.1rem;
                color: var(--ink-strong);
            }

            .mentor-role {
                color: var(--ink-soft);
                font-size: 0.9rem;
            }

            .mentor-saying {
                font-style: italic;
                color: var(--ink-soft);
                margin: 8px 0 0;
                line-height: 1.5;
            }

            .mentor-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                font-size: 0.85rem;
                color: var(--ink-muted);
            }

            .mentor-meta span {
                padding: 6px 12px;
                border-radius: 999px;
                background: var(--neutral-100);
                border: 1px solid rgba(20, 59, 46, 0.06);
            }

            /* FAQ Section */
            .faq-section {
                padding: 64px 0;
                background: var(--surface);
            }

            .faq-section .section-header {
                max-width: 640px;
                margin: 0 auto 32px;
                text-align: center;
            }

            .faq-section .section-title {
                margin: 0 0 8px;
                font-size: 1.9rem;
            }

            .faq-section .section-subtitle {
                margin: 0;
                color: var(--ink-soft);
                font-size: 0.95rem;
            }

            .faq-grid {
                max-width: 860px;
                margin: 0 auto;
                display: grid;
                gap: 14px;
            }

            .faq-grid details {
                border-radius: 14px;
                background: var(--neutral-100);
                border: 1px solid var(--neutral-100);
                padding: 10px 14px;
                transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
            }

            .faq-grid details[open] {
                background: #ffffff;
                border-color: rgba(63, 166, 126, 0.35);
                box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
            }

            .faq-grid summary {
                list-style: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                font-weight: 500;
                font-size: 0.98rem;
                padding: 4px 0;
            }

            .faq-grid summary::-webkit-details-marker {
                display: none;
            }

            .faq-grid summary::after {
                content: '+';
                flex-shrink: 0;
                width: 22px;
                height: 22px;
                border-radius: 999px;
                border: 1px solid #d1d5db;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.85rem;
                color: var(--ink-soft);
                transition: background 0.15s ease, transform 0.15s ease, color 0.15s ease, border-color 0.15s ease;
            }

            .faq-grid details[open] summary::after {
                content: '–';
                background: var(--primary-main);
                color: #ffffff;
                border-color: var(--primary-main);
                transform: rotate(0deg);
            }

            .faq-grid p {
                margin: 8px 2px 6px;
                color: var(--ink-soft);
                font-size: 0.94rem;
                line-height: 1.6;
            }

            /* ============== REDESIGNED FOOTER STYLES ============== */
            footer {
                background-color: var(--footer-bg);
                color: var(--footer-text);
                padding: 80px 0 32px;
                position: relative;
                border-top: 1px solid rgba(255, 255, 255, 0.05);
            }

            .footer-top {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr 1.2fr; /* Brand Wide, Link, Link, Contact */
                gap: 48px;
                padding-bottom: 64px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }

            .footer-brand-col {
                display: flex;
                flex-direction: column;
                gap: 20px;
                max-width: 360px;
            }

            .footer-logo {
                width: 160px;
                height: 110px;
                filter: brightness(0) invert(1); /* Membuat logo menjadi putih jika aslinya berwarna */
                opacity: 0.9;
            }

            .footer-desc {
                font-size: 0.95rem;
                line-height: 1.7;
                color: rgba(255, 255, 255, 0.6);
                margin: 0;
            }

            .footer-heading {
                font-size: 1.05rem;
                font-weight: 600;
                color: var(--footer-heading);
                margin-bottom: 24px;
                display: block;
                letter-spacing: 0.02em;
            }

            .footer-links {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .footer-links a {
                font-size: 0.95rem;
                color: var(--footer-text);
                transition: color 0.2s ease, padding-left 0.2s ease;
            }

            .footer-links a:hover {
                color: var(--primary-accent);
                padding-left: 4px;
            }

            .footer-contact-info {
                display: flex;
                flex-direction: column;
                gap: 18px;
            }

            .contact-row {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                font-size: 0.95rem;
                color: rgba(255, 255, 255, 0.75);
            }

            .contact-icon {
                margin-top: 3px;
                flex-shrink: 0;
                color: var(--primary-main);
            }

            .social-icons {
                display: flex;
                gap: 12px;
                margin-top: 8px;
            }

            .social-btn {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.05);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #ffffff;
                transition: background 0.2s ease, transform 0.2s ease;
            }

            .social-btn:hover {
                background: var(--primary-main);
                transform: translateY(-2px);
            }

            .footer-bottom {
                padding-top: 32px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 16px;
                font-size: 0.9rem;
                color: rgba(255, 255, 255, 0.5);
            }

            .footer-legal {
                display: flex;
                gap: 24px;
            }

            .footer-legal a:hover {
                color: #ffffff;
                text-decoration: underline;
            }

            /* Responsive adjustments for Footer */
            @media (max-width: 1024px) {
                .footer-top {
                    grid-template-columns: 1fr 1fr;
                    gap: 40px;
                }
            }

            @media (max-width: 640px) {
                .footer-top {
                    grid-template-columns: 1fr;
                    gap: 32px;
                }
                
                .footer-bottom {
                    flex-direction: column;
                    text-align: center;
                }

                .footer-legal {
                    justify-content: center;
                }
            }

            /* General Media Queries */
            @media (max-width: 1080px) {
                .articles-grid,
                .pricing-grid,
                .highlight-grid,
                .faq-grid,
                .testimonials-grid,
                .mentor-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 768px) {
                .pricing-section {
                    padding: 48px 0;
                }
                .pricing-card {
                    padding: 18px 16px 16px;
                }
                .articles-grid,
                .pricing-grid,
                .highlight-grid,
                .faq-grid,
                .testimonials-grid,
                .mentor-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        @php
            $joinLink = route('join');
            $profileLink = $profileLink ?? null;
            $profileAvatar = $profileAvatar ?? asset('images/avatar-placeholder.svg');
        @endphp

        <header>
            <nav>
                <div class="nav-inner">
                    <a class="brand" href="/">
                        <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" width="200" height="auto" />
                    </a>
                    <div class="nav-links">
                        <a href="#beranda">Beranda</a>
                        <a href="#artikel">Artikel</a>
                        <a href="#paket">Paket Belajar</a>
                        <a href="#keunggulan">Keunggulan</a>
                        <a href="#testimoni">Testimoni</a>
                        <a href="#faq">FAQ</a>
                    </div>
                    <div class="nav-actions">
                        @auth
                            <a
                                class="nav-profile"
                                href="{{ $profileLink ?? route('student.profile') }}"
                                aria-label="Buka profil"
                            >
                                <img src="{{ $profileAvatar }}" alt="Foto profil MayClass" />
                                <span class="sr-only">Menuju profil</span>
                            </a>
                            <form method="post" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="color: #000; border-color: #ccc;">Keluar</button>
                            </form>
                        @else
                            <a class="btn btn-primary" href="{{ $joinLink }}">
                                Gabung Sekarang
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>
            <div class="hero" id="beranda">
                <div class="hero-content" data-reveal data-reveal-delay="40">
                    <span class="badge">Bimbel MayClass</span>
                    <h1>Langkah Pasti Menuju Prestasi</h1>
                    <p>
                        Bertemu dengan tentor terbaik MayClass dan rasakan perjalanan belajar yang terarah, fleksibel, dan
                        penuh dukungan menuju Perguruan Tinggi impianmu.
                    </p>
                </div>
            </div>
        </header>

        <section class="section" id="artikel">
            <div class="container">
                <div class="section-header" data-reveal>
                    <h2 class="section-title">Wawasan Terbaru untuk Dukung Persiapanmu</h2>
                    <p class="section-subtitle">
                        Nikmati rangkuman materi, strategi ujian, dan cerita motivasi dari tim akademik MayClass agar kamu
                        selalu selangkah di depan.
                    </p>
                </div>
                <div class="articles-grid">
                    <article class="article-card" data-reveal>
                        <img
                            src="https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=800&q=80"
                            alt="Artikel UTBK"
                        />
                        <div class="article-content">
                            <h3>Kenali 7 Subtes UTBK yang Harus Kamu Taklukkan</h3>
                            <p>
                                Panduan lengkap memahami struktur TPS dan Literasi dengan latihan intensif dari mentor MayClass.
                            </p>
                            <a class="link-muted" href="{{ route('packages.index') }}">Selengkapnya</a>
                        </div>
                    </article>
                    <article class="article-card" data-reveal data-reveal-delay="120">
                        <img
                            src="https://images.unsplash.com/photo-1460518451285-97b6aa326961?auto=format&fit=crop&w=800&q=80"
                            alt="Artikel SKD"
                        />
                        <div class="article-content">
                            <h3>Strategi Lulus SKD ASN &amp; PPPK Bersama Mentor Ahli</h3>
                            <p>
                                Kisi-kisi terbaru, tips manajemen waktu, dan latihan soal real untuk skor maksimal di seleksi CPNS.
                            </p>
                            <a class="link-muted" href="{{ route('packages.index') }}">Selengkapnya</a>
                        </div>
                    </article>
                    <article class="article-card" data-reveal data-reveal-delay="200">
                        <img
                            src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=800&q=80"
                            alt="Artikel motivasi"
                        />
                        <div class="article-content">
                            <h3>Cerita Alumni: Raih Kampus Impian dari Nol</h3>
                            <p>
                                Belajar dari pengalaman siswa MayClass yang berhasil masuk kampus favorit berkat program intensif.
                            </p>
                            <a class="link-muted" href="{{ route('packages.index') }}">Selengkapnya</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="pricing-section" id="paket">
            <div class="container">
                <div class="section-header" data-reveal>
                    <h2 class="section-title">Pilih Paket Favoritmu &amp; Belajar Bareng Mentor Andal</h2>
                    <p class="section-subtitle">
                        Mulai dari kelas reguler, persiapan UTBK, hingga bimbingan CPNS—MayClass siap menemanimu dengan sesi
                        interaktif dan laporan perkembangan rutin.
                    </p>
                </div>
                @php($packageCatalog = collect($landingPackages ?? []))

                @if ($packageCatalog->isNotEmpty())
                    @foreach ($packageCatalog as $group)
                        <div class="pricing-group">
                            <div>
                                <h3>{{ $group['stage_label'] ?? $group['stage'] }}</h3>
                                @php($stageDescription = $group['stage_description'] ?? '')
                                @if (! empty($stageDescription))
                                    <p>{{ $stageDescription }}</p>
                                @endif
                            </div>
                            <div class="pricing-grid">
                                @foreach ($group['packages'] as $package)
                                    @php($features = collect($package['card_features'] ?? $package['features'] ?? [])->take(3))
                                    <article class="pricing-card" data-reveal data-reveal-delay="{{ $loop->index * 120 }}">
                                        <span class="badge" style="background: rgba(63, 166, 126, 0.12); color: var(--primary-main);">
                                            {{ $package['tag'] ?? ($group['stage_label'] ?? $group['stage']) }}
                                        </span>
                                        <strong>{{ $package['detail_title'] }}</strong>
                                        <div class="pricing-price">{{ $package['card_price'] }}</div>
                                        <div class="pricing-meta">
                                            <span>{{ $group['stage_label'] ?? $group['stage'] }}</span>
                                            @if (! empty($package['grade_range']))
                                                <span>• {{ $package['grade_range'] }}</span>
                                            @endif
                                        </div>
                                        @if ($package['summary'] ?? false)
                                            <p style="margin: 0; color: var(--ink-soft); font-size: 0.95rem;">
                                                {{ $package['summary'] }}
                                            </p>
                                        @endif
                                        @if ($features->isNotEmpty())
                                            <ul class="pricing-features">
                                                @foreach ($features as $feature)
                                                    <li>{{ $feature }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <div class="pricing-actions">
                                            <a class="btn btn-primary" href="{{ route('packages.show', $package['slug']) }}">Detail Paket</a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="pricing-grid">
                        <article class="pricing-card">
                            <strong>Paket sedang disiapkan</strong>
                            <div class="pricing-price">Segera hadir</div>
                            <p style="color: var(--ink-soft);">Tim MayClass dapat menambahkan paket melalui dashboard admin untuk menampilkan katalog otomatis di halaman ini.</p>
                            <div class="pricing-actions">
                                <a class="btn btn-primary" href="{{ route('login') }}">Masuk Dashboard</a>
                            </div>
                        </article>
                    </div>
                @endif
            </div>
        </section>

        <div class="highlight-section" id="keunggulan">
            <div class="highlight-content">
                <div data-reveal>
                    <span class="badge" style="background: rgba(255, 255, 255, 0.16); color: #ffffff;">Mengapa MayClass?</span>
                    <h2 style="margin: 18px 0 12px; font-size: clamp(2.1rem, 3vw, 3rem); color: #ffffff;">Bersama MayClass Belajarmu Lebih Seru</h2>
                    <p style="margin: 0; max-width: 620px; color: rgba(255, 255, 255, 0.84);">
                        Rasakan pengalaman belajar intensif, hangat, dan profesional. Tim MayClass memastikan setiap sesi
                        berjalan menyenangkan dengan target capaian yang jelas.
                    </p>
                </div>
                <div class="highlight-grid">
                    <div class="highlight-card" data-reveal>
                        <strong>Super Teacher</strong>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.82);">
                            Mentor pilihan dengan pengalaman mengajar panjang dan capaian prestisius.
                        </p>
                    </div>
                    <div class="highlight-card" data-reveal data-reveal-delay="120">
                        <strong>Materi Lengkap</strong>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.82);">
                            Silabus terbaru, bank soal adaptif, dan rekaman kelas siap diputar kapan pun.
                        </p>
                    </div>
                    <div class="highlight-card" data-reveal data-reveal-delay="200">
                        <strong>Analisis Mendalam</strong>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.82);">
                            Pantau progres lewat laporan mingguan dan rekomendasi belajar personal.
                        </p>
                    </div>
                    <div class="highlight-card" data-reveal data-reveal-delay="280">
                        <strong>Komunitas Aktif</strong>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.82);">
                            Saling dukung bersama teman sefrekuensi dan dapatkan motivasi harian.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <section class="section testimonials" id="testimoni">
            <div class="container">
                <div class="section-header" data-reveal>
                    <h2 class="section-title">Cerita Mereka yang Sudah Mewujudkan Mimpi</h2>
                    <p class="section-subtitle">
                        Dengar langsung pengalaman siswa MayClass yang berhasil menembus kampus favorit dan meraih skor tinggi
                        di ujian bergengsi.
                    </p>
                </div>
                <div class="testimonials-grid">
                    <article class="testimonial-card" data-reveal>
                        <div class="testimonial-rating" aria-label="Rating bintang lima">
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                        </div>
                        <p class="testimonial-quote">
                            “Mentor MayClass ramah banget dan jelas saat jelasin materi. Setiap sesi dibarengin <span>roadmap belajar</span>
                            jadi aku makin percaya diri menembus kampus impian.”
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">
                                <img src="{{ asset('images/jumbo.jpg') }}" alt="Triangga" />
                            </div>
                            <div class="testimonial-meta">
                                <strong>Triangga</strong>
                                <span>Skor UTBK 740</span>
                                <span class="testimonial-badge">Angkatan 2024</span>
                            </div>
                        </div>
                    </article>
                    <article class="testimonial-card" data-reveal data-reveal-delay="140">
                        <div class="testimonial-rating" aria-label="Rating bintang lima">
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                        </div>
                        <p class="testimonial-quote">
                            “Latihan soal dan pembahasan detailnya bantu aku ngejar target CPNS. Dashboard progres bikin aku sadar bagian
                            mana yang harus diperkuat sebelum tes.”
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">
                                <img src="{{ asset('images/pinguin.jpg') }}" alt="Xavier" />
                            </div>
                            <div class="testimonial-meta">
                                <strong>Xavier</strong>
                                <span>Skor SKD 480</span>
                                <span class="testimonial-badge">Lulus CPNS 2023</span>
                            </div>
                        </div>
                    </article>
                    <article class="testimonial-card" data-reveal data-reveal-delay="220">
                        <div class="testimonial-rating" aria-label="Rating bintang lima">
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                            <span aria-hidden="true">★</span>
                        </div>
                        <p class="testimonial-quote">
                            “MayClass bantu aku belajar lebih terarah dan konsisten. Mentor siap review tugas kapan aja dan ada sesi konsultasi
                            karier yang bikin aku mantap pilih jurusan.”
                        </p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">
                                <img src="{{ asset('images/lisa.jpg') }}" alt="Lisa" />
                            </div>
                            <div class="testimonial-meta">
                                <strong>Lisa</strong>
                                <span>Fasilkom UI</span>
                                <span class="testimonial-badge">Awardee Beasiswa</span>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section mentor-showcase" id="tentor">
            <div class="container">
                <div class="section-header" data-reveal>
                    <h2 class="section-title">Mentor Berkualitas Siap Mendampingi Belajarmu</h2>
                    <p class="section-subtitle">
                        Tenaga pendidik terbaik dari berbagai kampus unggulan siap memastikan setiap sesi belajar terasa dekat dan
                        menyenangkan.
                    </p>
                </div>
                <div class="mentor-grid">
                    <article class="mentor-profile" data-reveal>
                        <div class="mentor-avatar">
                            <img
                                src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80"
                                alt="Kak Henny"
                            />
                        </div>
                        <div class="mentor-info">
                            <strong>Kak Henny</strong>
                            <span class="mentor-role">Mentor Bahasa Indonesia &amp; Inggris</span>
                        </div>
                        <p class="mentor-saying">“Bangun mindset juara lewat storytelling dan latihan speaking yang konsisten.”</p>
                        <div class="mentor-meta">
                            <span>8+ tahun mengajar</span>
                            <span>700+ siswa dibimbing</span>
                        </div>
                    </article>
                    <article class="mentor-profile" data-reveal data-reveal-delay="160">
                        <div class="mentor-avatar">
                            <img
                                src="https://images.unsplash.com/photo-1504593811423-6dd665756598?auto=format&fit=crop&w=600&q=80"
                                alt="Kak Husein"
                            />
                        </div>
                        <div class="mentor-info">
                            <strong>Kak Husein</strong>
                        </div>
                        <p class="mentor-saying">“Tidak ada perjalanan sulit jika kita pecah jadi milestone kecil yang terukur.”</p>
                        <div class="mentor-meta">
                            <span>Certified Math Trainer</span>
                            <span>Rata-rata nilai siswa 87</span>
                        </div>
                    </article>
                    <article class="mentor-profile" data-reveal data-reveal-delay="240">
                        <div class="mentor-avatar">
                            <img
                                src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=600&q=80"
                                alt="Kak Pal"
                            />
                        </div>
                        <div class="mentor-info">
                            <strong>Kak Pal</strong>
                            <span class="mentor-role">Mentor SKD &amp; TPA</span>
                        </div>
                        <p class="mentor-saying">“Strategi tepat, simulasi rutin, dan evaluasi detail bikin kamu siap setiap ujian.”</p>
                        <div class="mentor-meta">
                            <span>Coach CPNS Favorite</span>
                            <span>95% siswa lulus seleksi</span>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section faq-section" id="faq">
            <div class="container">
                <div class="section-header" data-reveal>
                    <h2 class="section-title">FAQ</h2>
                </div>

                <div class="faq-grid" data-reveal>
                    <details>
                        <summary>Apakah MayClass menyediakan kelas online dan tatap muka?</summary>
                        <p>
                            Ya. Kamu bisa memilih mode belajar sesuai kebutuhan. Tim kami bantu atur jadwal dan mentor terbaik
                            untukmu.
                        </p>
                    </details>
                    <details>
                        <summary>Bagaimana cara mengakses materi dan rekaman kelas?</summary>
                        <p>
                            Siswa dapat login ke dashboard MayClass untuk melihat materi, rekaman kelas, dan rangkuman progres
                            belajar.
                        </p>
                    </details>
                    <details>
                        <summary>Apakah bisa reschedule jika ada jadwal mendadak?</summary>
                        <p>
                            Bisa. Hubungi admin maksimal 24 jam sebelum sesi dimulai dan kami akan bantu atur ulang jadwalmu.
                        </p>
                    </details>
                    <details>
                        <summary>Bagaimana sistem evaluasi progres siswa?</summary>
                        <p>
                            Kami menyediakan laporan mingguan, evaluasi tryout, dan coaching pribadi agar target belajar tercapai.
                        </p>
                    </details>
                    <details>
                        <summary>Apakah ada grup diskusi komunitas?</summary>
                        <p>
                            Ada. Semua siswa akan bergabung di komunitas eksklusif untuk diskusi, motivasi, dan info terbaru.
                        </p>
                    </details>
                    <details>
                        <summary>Metode pembayaran apa yang tersedia?</summary>
                        <p>
                            Pembayaran dapat melalui transfer bank, e-wallet, dan virtual account dengan konfirmasi otomatis.
                        </p>
                    </details>
                </div>
            </div>
        </section>

        {{-- REDESIGNED FOOTER START --}}
        <footer>
            <div class="container">
                <div class="footer-top">
                    <div class="footer-brand-col">
                        <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" class="footer-logo" />
                        <p class="footer-desc">
                            MayClass adalah platform bimbingan belajar premium yang menggabungkan materi berkualitas, mentor berpengalaman, dan teknologi terkini untuk mengantarkan siswa menuju prestasi akademik terbaik.
                        </p>
                        <div class="social-icons">
                            <a href="#" class="social-btn" aria-label="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            </a>
                            <a href="#" class="social-btn" aria-label="TikTok">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 448 512"><path d="M448 209.91a210.06 210.06 0 0 1-122.77-39.25V349.38A162.55 162.55 0 1 1 185 188.31V278.2a74.62 74.62 0 1 0 52.23 71.18V0l88 0a121.18 121.18 0 0 0 1.86 22.17h0A122.18 122.18 0 0 0 381 102.39a121.43 121.43 0 0 0 67 20.14z"/></svg>
                            </a>
                            <a href="#" class="social-btn" aria-label="YouTube">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.25z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <span class="footer-heading">Program</span>
                        <div class="footer-links">
                            <a href="#paket">Paket Belajar</a>
                            <a href="#tentor">Super Teacher</a>
                            <a href="#testimoni">Cerita Alumni</a>
                            <a href="{{ route('packages.index') }}">Katalog Lengkap</a>
                        </div>
                    </div>

                    <div>
                        <span class="footer-heading">Bantuan</span>
                        <div class="footer-links">
                            <a href="#faq">FAQ (Tanya Jawab)</a>
                            <a href="{{ route('login') }}">Masuk Dashboard</a>
                            <a href="{{ route('join') }}">Daftar Siswa Baru</a>
                            <a href="https://wa.me/6281234567890" target="_blank">Hubungi Admin</a>
                        </div>
                    </div>

                    <div>
                        <span class="footer-heading">Hubungi Kami</span>
                        <div class="footer-contact-info">
                            <div class="contact-row">
                                <svg class="contact-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                <span>Jl. Pendidikan No. 123, Jakarta Selatan, DKI Jakarta 12430</span>
                            </div>
                            <div class="contact-row">
                                <svg class="contact-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.12 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span>+62 812-3456-7890</span>
                            </div>
                            <div class="contact-row">
                                <svg class="contact-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                <span>hello@mayclass.id</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} MayClass Education. All rights reserved.</p>
                    <div class="footer-legal">
                        <a href="#">Kebijakan Privasi</a>
                        <a href="#">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </footer>
        {{-- REDESIGNED FOOTER END --}}

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const root = document.documentElement;

                if (!root || root.dataset.page !== 'landing') {
                    return;
                }

                const revealElements = Array.from(document.querySelectorAll('[data-reveal]'));
                const motionQuery = window.matchMedia
                    ? window.matchMedia('(prefers-reduced-motion: reduce)')
                    : null;
                let revealObserver = null;

                const disconnectObserver = () => {
                    if (revealObserver) {
                        revealObserver.disconnect();
                        revealObserver = null;
                    }
                };

                const revealImmediately = () => {
                    if (!revealElements.length) {
                        return;
                    }

                    revealElements.forEach((element) => {
                        element.classList.add('is-visible');
                        element.style.removeProperty('--reveal-delay');
                    });

                    disconnectObserver();
                };

                const setupRevealObserver = () => {
                    if (!revealElements.length) {
                        return;
                    }

                    disconnectObserver();

                    if (motionQuery && motionQuery.matches) {
                        revealImmediately();
                        return;
                    }

                    revealObserver = new IntersectionObserver(
                        (entries) => {
                            entries.forEach((entry) => {
                                if (!entry.isIntersecting) {
                                    return;
                                }

                                const element = entry.target;
                                const delay = Number(element.dataset.revealDelay || 0);

                                if (Number.isFinite(delay) && !element.style.getPropertyValue('--reveal-delay')) {
                                    element.style.setProperty('--reveal-delay', `${delay}ms`);
                                }

                                requestAnimationFrame(() => {
                                    element.classList.add('is-visible');
                                });

                                if (revealObserver) {
                                    revealObserver.unobserve(element);
                                }
                            });
                        },
                        {
                            threshold: 0.25,
                            rootMargin: '0px 0px -10% 0px',
                        }
                    );

                    revealElements.forEach((element) => {
                        if (element.classList.contains('is-visible')) {
                            return;
                        }

                        revealObserver.observe(element);
                    });
                };

                setupRevealObserver();

                if (motionQuery) {
                    motionQuery.addEventListener('change', (event) => {
                        if (event.matches) {
                            revealImmediately();
                        } else {
                            setupRevealObserver();
                        }
                    });
                }

                if (motionQuery && motionQuery.matches) {
                    return;
                }

                const scrollElement = document.scrollingElement || root;

                if (!scrollElement) {
                    return;
                }

                document.querySelectorAll('a[href^="#"]').forEach((link) => {
                    link.addEventListener('click', (event) => {
                        const href = link.getAttribute('href');

                        if (!href || href.length <= 1) {
                            return;
                        }

                        const anchorTarget = document.querySelector(href);

                        if (!anchorTarget) {
                            return;
                        }

                        event.preventDefault();

                        const offset = 80;
                        const desired =
                            anchorTarget.getBoundingClientRect().top + window.pageYOffset - offset;
                        const maxScroll = scrollElement.scrollHeight - window.innerHeight;
                        const target = Math.min(Math.max(desired, 0), maxScroll);

                        window.scrollTo({ top: target, behavior: 'smooth' });
                    });
                });
            });
        </script>

    </body>
</html>