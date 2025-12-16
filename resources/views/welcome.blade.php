<!DOCTYPE html>
<html lang="id" data-page="landing">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MayClass - Bimbingan Belajar Premium untuk Raih Prestasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            --footer-bg: #0d261f;
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
            overflow-x: hidden;
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

        .section {
            width: 100%;
            padding: 96px 0;
        }

        .section .container {
            width: 100%;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 16px;
            }

            .section {
                padding: 48px 0;
            }
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
            padding: 14px clamp(12px, 3vw, 24px);
            background: rgba(255, 254, 254, 0.52);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .nav-inner {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            width: 100%;
            padding: 0 32px;
            gap: 20px;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            margin-right: 8px;
            z-index: 1001;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #000;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        .nav-actions-desktop {
            display: flex;
        }

        .nav-links .nav-actions {
            display: none;
        }

        @media (max-width: 768px) {
            .nav-actions-desktop {
                display: none !important;
            }

            .nav-links .nav-actions {
                display: flex;
            }

        }

        @media (max-width: 1024px) {
            nav {
                padding: 12px clamp(8px, 4vw, 20px);
            }

            .nav-inner {
                gap: clamp(18px, 4vw, 32px);
            }
        }

        @media (max-width: 768px) {
            nav {
                padding: 6px 20px;
            }

            .nav-inner {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                padding: 0;
                gap: 0;
            }

            .hamburger {
                display: flex;
                margin-right: 0;
            }

            .nav-links {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: #e6f7f1;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
                padding: 80px 24px 24px;
                gap: 24px;
                margin-left: 0;
                box-shadow: -4px 0 24px rgba(0, 0, 0, 0.1);
                transition: right 0.3s ease;
                z-index: 1000;
            }

            .nav-links.active {
                right: 0;
            }

            .nav-links a {
                font-size: 1.1rem;
                padding: 8px 0;
                width: 100%;
            }

            .nav-actions {
                display: flex;
                flex-direction: column;
                width: 100%;
                gap: 12px;
                margin-top: 8px;
                padding-top: 20px;
                border-top: 1px solid rgba(63, 166, 126, 0.2);
            }

            .nav-actions .btn {
                width: 100%;
                justify-content: center;
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

        .brand img {
            height: 42px;
            width: auto;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .brand img {
                height: 56px !important;
            }
        }

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 28px;
            font-size: 0.95rem;
            margin-left: 0;
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

        .nav-icon-btn {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-strong);
            border-radius: 50%;
            transition: background 0.2s;
            margin-right: 4px;
        }

        .nav-icon-btn:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 10px;
            height: 10px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
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
                height: 30px;
            }

            /* Mobile sidebar profile photo - circular style */
            .nav-links .nav-profile {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                border: 3px solid var(--primary-main);
                overflow: hidden;
                flex-shrink: 0;
                background: #fff;
                box-shadow: 0 4px 16px rgba(63, 166, 126, 0.25);
                display: block;
                margin: 0 auto;
            }

            .nav-links .nav-actions {
                align-items: center;
            }

            .nav-links .nav-profile img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
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

        .btn-danger {
            background: #ef4444;
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            border: none;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
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
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        @media (max-width: 768px) {
            .hero {
                height: auto;
                min-height: 100vh;
                padding-top: calc(var(--nav-height) + 40px);
                padding-bottom: 60px;
                padding-left: 16px;
                padding-right: 16px;
            }
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

        @media (max-width: 768px) {
            .hero h1 {
                font-size: clamp(2rem, 6vw, 2.7rem);
                margin: 12px 0;
            }

            .hero p {
                font-size: 1rem;
                margin: 0 0 24px;
            }
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
            justify-content: center;
        }

        @media (max-width: 768px) {
            .hero-actions {
                margin-bottom: 24px;
                gap: 12px;
            }
        }

        /* Hero Statistics Cards */
        .hero-stats {
            display: flex;
            gap: 24px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 48px;
            width: 100%;
        }

        .stat-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 28px 40px;
            min-width: 220px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1),
                0 1px 3px rgba(255, 255, 255, 0.3) inset;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg,
                    rgba(132, 217, 134, 0.8) 0%,
                    rgba(168, 230, 161, 0.8) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2),
                0 2px 6px rgba(255, 255, 255, 0.4) inset;
            border-color: rgba(255, 255, 255, 0.4);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg,
                    rgba(132, 217, 134, 0.3) 0%,
                    rgba(168, 230, 161, 0.3) 100%);
            border-radius: 50%;
            margin-bottom: 16px;
            font-size: 28px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(132, 217, 134, 0.2);
            overflow: hidden;
        }

        .stat-icon-photo {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            border: 3px solid rgba(255, 255, 255, 0.8);
        }

        .stat-icon-photo.active {
            opacity: 1;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(132, 217, 134, 0.4);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 8px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            letter-spacing: -0.02em;
        }

        .stat-label {
            font-size: 0.95rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.02em;
            text-align: center;
        }

        @media (max-width: 768px) {
            .hero-stats {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                gap: 12px;
                margin-top: 24px;
                width: 100%;
                justify-content: center;
            }

            .stat-card {
                flex: 1;
                max-width: 160px;
                min-width: auto;
                padding: 16px 12px;
                border-radius: 14px;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
                margin-bottom: 8px;
            }

            .stat-icon-photo {
                width: 36px;
                height: 36px;
            }

            .stat-number {
                font-size: 1.5rem;
                margin-bottom: 2px;
            }

            .stat-label {
                font-size: 0.7rem;
            }
        }

        /* Keyframe Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes floatReverse {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Apply animations to stat cards */
        .stat-card:nth-child(1) {
            animation: float 4s ease-in-out infinite;
            animation-delay: 0s;
        }

        .stat-card:nth-child(2) {
            animation: floatReverse 4.5s ease-in-out infinite;
            animation-delay: 0.3s;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
            animation: shimmer 3s infinite;
        }

        .stat-icon {
            animation: pulse 2.5s ease-in-out infinite;
        }

        .stat-card:nth-child(1) .stat-icon {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(2) .stat-icon {
            animation-delay: 0.5s;
        }

        .stat-number {
            animation: countUp 0.6s ease-out;
        }

        /* Pause animations on hover for better UX */
        .stat-card:hover {
            animation-play-state: paused;
        }

        .stat-card:hover .stat-icon {
            animation-play-state: paused;
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
            gap: 32px;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .articles-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .articles-grid {
                display: flex;
                flex-direction: column;
                gap: 0;
                padding-bottom: 0;
                max-height: 420px;
                overflow-y: auto;
                scroll-snap-type: y mandatory;
                scroll-behavior: smooth;
            }
        }

        .article-card {
            background: var(--surface);
            border-radius: 24px;
            border: 1px solid rgba(20, 59, 46, 0.06);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            height: 100%;
            position: relative;
        }

        @media (max-width: 768px) {
            .article-card {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                min-height: 400px;
                flex-shrink: 0;
                scroll-snap-align: start;
                margin-bottom: 24px;
            }

            .article-image-wrapper {
                height: 160px !important;
            }

            .article-content {
                padding: 16px !important;
            }

            .article-content h3 {
                font-size: 1.1rem !important;
                line-height: 1.3 !important;
                margin-bottom: 6px !important;
            }

            .article-content p {
                display: block !important;
                font-size: 0.9rem !important;
                margin-bottom: 12px !important;
            }

            .article-footer {
                padding-top: 12px !important;
            }

            .btn-read-more {
                padding: 0 !important;
                font-size: 0.85rem !important;
            }

            .article-badge {
                font-size: 0.6rem !important;
                padding: 4px 8px !important;
            }
        }

        .article-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(31, 107, 79, 0.15);
            border-color: rgba(63, 166, 126, 0.3);
        }

        .article-image-wrapper {
            position: relative;
            width: 100%;
            height: 160px;
            overflow: hidden;
        }

        .article-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .article-card:hover img {
            transform: scale(1.1);
        }

        .article-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 2;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .article-content {
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
            background: #ffffff;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: var(--ink-muted);
            margin-bottom: 4px;
        }

        .article-meta svg {
            width: 16px;
            height: 16px;
            color: var(--primary-main);
        }

        .article-content h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--ink-strong);
            line-height: 1.4;
            transition: color 0.2s ease;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .article-card:hover .article-content h3 {
            color: var(--primary-main);
        }

        .article-content p {
            margin: 0;
            color: var(--ink-soft);
            font-size: 0.95rem;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .article-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding-top: 20px;
        }

        .btn-read-more {
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.2s ease, color 0.2s ease;
        }

        .article-card:hover .btn-read-more {
            gap: 12px;
            color: var(--primary-main);
        }

        .btn-read-more svg {
            width: 18px;
            height: 18px;
            transition: transform 0.2s ease;
        }

        .article-card:hover .btn-read-more svg {
            transform: translateX(2px);
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

        /* UPDATED PRICING LAYOUT */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .pricing-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        .pricing-card {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 24px;
            border-radius: 20px;
            background: var(--surface);
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.04);
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .pricing-card {
                padding: 16px !important;
                gap: 10px !important;
                width: 220px !important;
                min-width: 220px !important;
                max-width: 220px !important;
                flex-shrink: 0;
                box-sizing: border-box;
                scroll-snap-align: start;
                border-radius: 14px !important;
            }

            .pricing-card .badge {
                padding: 5px 10px !important;
                font-size: 0.7rem !important;
            }

            .pricing-card strong {
                font-size: 0.9rem !important;
            }

            .pricing-price {
                font-size: 1.2rem !important;
                margin: 4px 0 !important;
            }

            .pricing-meta {
                font-size: 0.75rem !important;
                padding: 3px 8px !important;
            }

            .pricing-features {
                font-size: 0.8rem !important;
                gap: 8px !important;
            }

            .pricing-actions {
                padding-top: 12px !important;
            }

            .pricing-actions .btn {
                font-size: 0.85rem !important;
                padding: 10px !important;
                border-radius: 10px !important;
            }
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
            border-color: rgba(63, 166, 126, 0.4);
        }

        .pricing-card .badge {
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(63, 166, 126, 0.1);
            color: var(--primary-main);
        }

        .pricing-card strong {
            font-size: 1.15rem;
            line-height: 1.4;
            color: var(--ink-strong);
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .pricing-price {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 4px 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .pricing-meta {
            display: inline-block;
            font-size: 0.85rem;
            color: var(--ink-soft);
            padding: 4px 10px;
            background: #f1f5f9;
            border-radius: 6px;
            align-self: flex-start;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 8px 0 0;
            display: grid;
            gap: 10px;
            font-size: 0.92rem;
            color: var(--ink-soft);
            flex-grow: 1;
            /* Dorong tombol ke bawah */
        }

        .pricing-features li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .pricing-features li::before {
            content: '✔';
            color: var(--primary-main);
            font-weight: 800;
            flex-shrink: 0;
            margin-top: 1px;
        }

        @media (max-width: 768px) {
            .pricing-features li {
                word-wrap: break-word;
                overflow-wrap: break-word;
                max-width: 100%;
            }
        }

        /* --- CSS TOMBOL SEJAJAR --- */
        .pricing-actions {
            margin-top: auto;
            /* Memaksa tombol turun ke bawah */
            width: 100%;
            padding-top: 20px;
            /* Memberi jarak aman */
        }

        .pricing-actions .btn {
            width: 100%;
            font-size: 0.95rem;
            border-radius: 12px;
            padding: 12px;
            text-decoration: none;
            justify-content: center;
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
                url("https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1600&q=80") center/cover;
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

        @media (max-width: 768px) {
            .highlight-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
            }

            .highlight-card {
                padding: 14px !important;
                border-radius: 12px !important;
                gap: 6px !important;
            }

            .highlight-card strong {
                font-size: 0.8rem !important;
                line-height: 1.3 !important;
            }

            .highlight-card p {
                font-size: 0.7rem !important;
                line-height: 1.4 !important;
                margin: 0 !important;
            }
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

        @media (max-width: 768px) {
            .testimonials-grid {
                gap: 16px;
            }
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

        @media (max-width: 768px) {
            .testimonial-card {
                padding: 24px;
                min-height: auto;
            }
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

        @media (max-width: 768px) {
            .mentor-grid {
                gap: 16px;
            }
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

        @media (max-width: 768px) {
            .mentor-profile {
                padding: 14px !important;
                gap: 10px !important;
                border-radius: 16px !important;
            }

            .mentor-avatar {
                width: 60px !important;
                height: 60px !important;
                border-radius: 18px !important;
            }

            .mentor-info strong {
                font-size: 0.85rem !important;
            }

            .mentor-role {
                font-size: 0.7rem !important;
            }

            .mentor-saying {
                font-size: 0.75rem !important;
                line-height: 1.4 !important;
            }

            .mentor-meta span {
                font-size: 0.65rem !important;
                padding: 3px 8px !important;
            }
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
            padding: 40px 0 32px;
            position: relative;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-top {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.2fr;
            /* Brand Wide, Link, Link, Contact */
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
            filter: brightness(0) invert(1);
            /* Membuat logo menjadi putih jika aslinya berwarna */
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .footer-brand-col {
                align-items: center;
                text-align: center;
            }

            .footer-logo {
                width: 120px;
                height: 80px;
                margin: 0 auto;
            }
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

        .contact-link {
            display: inline-flex;
            align-items: flex-start;
            gap: 12px;
            color: inherit;
            transition: opacity 0.2s;
        }

        .contact-link:hover {
            opacity: 0.9;
            color: #ffffff;
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
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 16px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
            text-align: center;
            width: 100%;
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

        /* --- FILTER DROPDOWN STYLES --- */
        .filter-container {
            position: relative;
            width: 100%;
            max-width: 280px;
            margin: 0 auto 48px;
            z-index: 50;
        }

        .filter-btn {
            width: 100%;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 1rem;
            font-weight: 500;
            color: var(--ink-strong);
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            border-color: var(--primary-main);
            box-shadow: 0 8px 16px rgba(63, 166, 126, 0.12);
        }

        .filter-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 100%;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transform-origin: top;
        }

        .filter-option {
            padding: 12px 20px;
            cursor: pointer;
            transition: background 0.2s;
            font-size: 0.95rem;
            color: var(--ink-soft);
        }

        .filter-option:hover {
            background: rgba(63, 166, 126, 0.08);
            color: var(--primary-main);
        }

        .filter-option.active {
            background: rgba(63, 166, 126, 0.12);
            color: var(--primary-dark);
            font-weight: 600;
        }

        /* --- SWIPER CUSTOM STYLES --- */
        .swiper {
            width: 100%;
            padding-bottom: 60px !important;
            padding-top: 20px !important;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
            height: auto;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--primary-main) !important;
            background: rgba(255, 255, 255, 0.95);
            width: 48px !important;
            height: 48px !important;
            border-radius: 50%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: var(--primary-main);
            color: #fff !important;
            box-shadow: 0 10px 25px rgba(63, 166, 126, 0.4);
            transform: scale(1.05);
            border-color: var(--primary-main);
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 22px !important;
            font-weight: bold;
        }

        .swiper-pagination-bullet {
            width: 10px !important;
            height: 10px !important;
            background: #cbd5e1 !important;
            opacity: 1 !important;
            transition: all 0.3s ease;
            margin: 0 6px !important;
        }

        .swiper-pagination-bullet-active {
            width: 32px !important;
            border-radius: 10px !important;
            background: var(--primary-main) !important;
        }

        /* Testimonial Card Redesign */
        .testimonial-card {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            padding: 48px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.03);
            text-align: center;
            align-items: center;
            position: relative;
        }

        .testimonial-quote {
            font-size: 1.25rem;
            font-style: italic;
            color: var(--ink-strong);
            line-height: 1.8;
            font-weight: 500;
        }

        .testimonial-author {
            flex-direction: column;
            gap: 12px;
            margin-top: 16px;
        }

        .testimonial-avatar {
            width: 88px;
            height: 88px;
            border-width: 3px;
            border-color: var(--primary-main);
        }

        .testimonial-meta {
            align-items: center;
        }

        /* Mentor Card Redesign */
        .mentor-profile {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            padding: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease;
        }

        .mentor-profile:hover {
            transform: translateY(-5px);
        }

        .mentor-profile::after {
            display: none;
        }

        .mentor-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            margin-bottom: 12px;
            border: 4px solid rgba(63, 166, 126, 0.15);
        }

        @media (max-width: 768px) {
            .testimonial-card {
                padding: 16px !important;
                gap: 12px !important;
                border-radius: 16px !important;
                min-height: auto !important;
            }

            .testimonial-rating {
                font-size: 0.9rem !important;
            }

            .testimonial-quote {
                font-size: 0.85rem !important;
                line-height: 1.5 !important;
            }

            .testimonial-avatar {
                width: 50px !important;
                height: 50px !important;
            }

            .testimonial-meta strong {
                font-size: 0.85rem !important;
            }

            .testimonial-meta span {
                font-size: 0.75rem !important;
            }

            .swiper-button-next,
            .swiper-button-prev {
                display: none !important;
            }

            .mentor-profile {
                max-width: 100%;
            }
        }

        /* ============ HORIZONTAL SCROLL CONTAINERS (Mobile) ============ */
        @media (max-width: 1024px) {

            /* Base horizontal scroll container */
            .articles-grid,
            .testimonials-grid,
            .mentor-grid,
            .pricing-grid,
            .highlight-grid {
                display: flex !important;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                gap: 16px;
                padding: 8px 0px 24px;
                scrollbar-width: thin;
                scrollbar-color: rgba(63, 166, 126, 0.3) rgba(0, 0, 0, 0.05);
            }

            .articles-grid::-webkit-scrollbar,
            .testimonials-grid::-webkit-scrollbar,
            .mentor-grid::-webkit-scrollbar,
            .pricing-grid::-webkit-scrollbar,
            .highlight-grid::-webkit-scrollbar {
                height: 6px;
            }

            .articles-grid::-webkit-scrollbar-track,
            .testimonials-grid::-webkit-scrollbar-track,
            .mentor-grid::-webkit-scrollbar-track,
            .pricing-grid::-webkit-scrollbar-track,
            .highlight-grid::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.05);
                border-radius: 10px;
            }

            .articles-grid::-webkit-scrollbar-thumb,
            .testimonials-grid::-webkit-scrollbar-thumb,
            .mentor-grid::-webkit-scrollbar-thumb,
            .pricing-grid::-webkit-scrollbar-thumb,
            .highlight-grid::-webkit-scrollbar-thumb {
                background: rgba(63, 166, 126, 0.4);
                border-radius: 10px;
            }

            .articles-grid::-webkit-scrollbar-thumb:hover,
            .testimonials-grid::-webkit-scrollbar-thumb:hover,
            .mentor-grid::-webkit-scrollbar-thumb:hover,
            .pricing-grid::-webkit-scrollbar-thumb:hover,
            .highlight-grid::-webkit-scrollbar-thumb:hover {
                background: var(--primary-main);
            }

            /* Individual items */
            .article-card,
            .testimonial-card,
            .mentor-profile,
            .pricing-card,
            .highlight-card {
                flex: 0 0 85%;
                scroll-snap-align: start;
                max-width: 85%;
            }

            /* Documentation grid */
            div[style*="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr))"] {
                display: flex !important;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                gap: 16px !important;
                padding: 8px 0px 24px !important;
                scrollbar-width: thin;
            }

            div[style*="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr))"]>div {
                flex: 0 0 85% !important;
                scroll-snap-align: start;
                max-width: 85%;
            }
        }

        @media (max-width: 768px) {

            /* Reduce hero typography */
            .hero h1 {
                font-size: clamp(1.75rem, 5.5vw, 2.4rem) !important;
                margin: 10px 0 !important;
                line-height: 1.2 !important;
            }

            .hero p {
                font-size: 0.95rem !important;
                margin: 0 0 20px !important;
            }

            .badge {
                padding: 8px 14px !important;
                font-size: 0.85rem !important;
            }

            /* Section headers */
            .section-header h2 {
                font-size: clamp(1.5rem, 4vw, 2rem) !important;
            }

            .section-header p {
                font-size: 0.9rem !important;
            }

            /* Reduce section padding */
            .section {
                padding: 48px 0 !important;
            }

            .pricing-section {
                padding: 40px 0 !important;
            }

            .highlight-section {
                padding: 48px 32px !important;
            }

            /* Optimize cards for mobile */
            .article-card {
                grid-template-rows: 160px 1fr !important;
            }

            .article-content {
                padding: 16px !important;
            }

            .article-content h3 {
                font-size: 1rem !important;
            }

            .article-content p {
                font-size: 0.85rem !important;
            }

            .testimonial-card {
                padding: 20px !important;
                min-height: auto !important;
            }

            .testimonial-quote {
                font-size: 0.95rem !important;
            }

            .mentor-profile {
                padding: 18px !important;
            }

            .mentor-info strong {
                font-size: 1rem !important;
            }

            .mentor-saying {
                font-size: 0.9rem !important;
            }

            .pricing-card {
                padding: 18px 16px 16px !important;
            }

            .pricing-card strong {
                font-size: 1.05rem !important;
            }

            .pricing-price {
                font-size: 1.4rem !important;
            }

            .pricing-features {
                font-size: 0.85rem !important;
            }

            .highlight-card {
                padding: 18px !important;
            }

            .highlight-card strong {
                font-size: 1.05rem !important;
            }

            /* Buttons - touch friendly */
            .btn {
                padding: 14px 24px !important;
                font-size: 0.9rem !important;
                min-height: 44px;
            }

            .hero-actions {
                margin-bottom: 20px !important;
                gap: 10px !important;
                flex-direction: column;
                width: 100%;
                max-width: 280px;
            }

            .hero-actions .btn {
                width: 100%;
            }

            /* Pricing group header */
            .pricing-group-header h3 {
                font-size: 1.3rem !important;
                padding: 6px 24px !important;
            }

            .pricing-group-header p {
                font-size: 0.9rem !important;
            }

            /* Highlight section text */
            .highlight-section h2 {
                font-size: clamp(1.6rem, 4vw, 2.4rem) !important;
            }

            /* FAQ */
            .faq-grid {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 8px !important;
            }

            .faq-grid details {
                padding: 8px 12px !important;
                border-radius: 10px !important;
            }

            .faq-grid summary {
                font-size: 0.8rem !important;
                padding: 2px 0 !important;
            }

            .faq-grid summary::after {
                width: 18px !important;
                height: 18px !important;
                font-size: 0.7rem !important;
            }

            .faq-grid p {
                font-size: 0.75rem !important;
                margin: 6px 0 4px !important;
                line-height: 1.5 !important;
            }
        }

        /* Tablet adjustments */
        @media (min-width: 641px) and (max-width: 1024px) {

            .article-card,
            .testimonial-card,
            .mentor-profile,
            .pricing-card,
            .highlight-card {
                flex: 0 0 48% !important;
                max-width: 48%;
            }

            div[style*="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr))"]>div {
                flex: 0 0 48% !important;
                max-width: 48%;
            }
        }

        /* Desktop - revert to grid (>1024px) */
        @media (min-width: 1025px) {
            .articles-grid {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .testimonials-grid,
            .mentor-grid {
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            }

            .highlight-grid {
                display: grid !important;
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
            padding: 20px;
        }

        .modal-container {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @media (max-width: 768px) {
            .modal-container {
                max-width: 90%;
                width: 90%;
                border-radius: 16px;
                height: auto;
                max-height: 80vh;
                position: relative;
                margin: auto;
                bottom: auto;
            }
        }

        /* New Responsive Modal Classes */
        .modal-body {
            padding: 32px;
            width: 100%;
            max-width: 900px;
        }

        .modal-content-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 40px;
            align-items: start;
        }

        .modal-title {
            font-size: 2.2rem;
            margin: 0 0 12px;
            line-height: 1.2;
            font-weight: 800;
            color: var(--text-main);
        }

        @media (max-width: 768px) {
            .modal-body {
                padding: 20px;
            }

            .modal-content-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .modal-title {
                font-size: 1.4rem;
            }
        }

        @keyframes modalPop {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s;
            z-index: 10;
        }

        .modal-close:hover {
            background: #f1f5f9;
            color: var(--danger);
        }
    </style>
</head>

<body x-data="{ 
    selectedLevel: 'SD', 
    openDropdown: false,
    modalOpen: false, 
    pkg: {}, 
    openModal(packageData) { 
        this.pkg = packageData; 
        this.modalOpen = true; 
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        this.modalOpen = false;
        document.body.style.overflow = 'auto';
    }
}">@php
    $joinLink = route('join');
    $profileLink = $profileLink ?? null;
    $profileAvatar = $profileAvatar ?? asset('images/avatar-placeholder.svg');

    // Check for pending order
    $pendingOrder = null;
    // Check for active/approved package
    $hasActivePackage = false;

    if (Auth::check() && in_array(Auth::user()->role, ['student', 'visitor'])) {
        // Check for pending order (initiated, pending, awaiting_payment, awaiting_verification)
        $pendingOrder = Auth::user()->orders()
            ->whereIn('status', ['initiated', 'pending', 'awaiting_payment', 'awaiting_verification'])
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->with('package')
            ->latest()
            ->first();

        // Check for active/approved package (Enrollment is the source of truth for active access)
        $hasActivePackage = Auth::user()->enrollments()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->exists();
    }
@endphp

    <header>
        <nav>
            <div class="nav-inner">
                <a class="brand" href="#beranda">
                    <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" width="200" height="auto" />
                </a>
                <button class="hamburger" aria-label="Toggle menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="nav-links">
                    <a href="#artikel">Artikel</a>
                    <a href="#paket">Paket Belajar</a>
                    <a href="#keunggulan">Keunggulan</a>
                    <a href="#testimoni">Testimoni</a>
                    <a href="#faq">FAQ</a>
                    <div class="nav-actions">
                        {{-- Mobile Nav Actions --}}
                        @auth
                            <div style="display: flex; justify-content: center; width: 100%; margin-bottom: 16px;">
                                <a class="nav-profile" href="{{ $profileLink ?? route('student.profile') }}"
                                    aria-label="Buka profil"
                                    style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; aspect-ratio: 1/1;">
                                    <img src="{{ $profileAvatar }}" alt="Foto profil"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" />
                                </a>
                            </div>

                            {{-- LOGIKA TOMBOL MOBILE --}}
                            @if(auth()->user()->role === 'admin')
                                <a class="btn btn-primary" href="{{ route('admin.dashboard') }}"
                                    style="background: #0f766e; border-color: #0f766e; color: white; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Dashboard
                                </a>
                            @elseif(auth()->user()->role === 'tutor')
                                <a class="btn btn-primary" href="{{ route('tutor.dashboard') }}"
                                    style="background: #0f766e; border-color: #0f766e; color: white; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Ayo Mengajar
                                </a>
                            @elseif($hasActivePackage)
                                {{-- 1. SUDAH LUNAS / AKTIF --}}
                                <a class="btn btn-primary" href="{{ route('student.dashboard') }}"
                                    style="background: #0f766e; border-color: #0f766e; color: white; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Ayo Belajar
                                </a>
                            @elseif($pendingOrder && $pendingOrder->package)
                                {{-- 2. ADA TRANSAKSI BERJALAN --}}
                                @if($pendingOrder->status === 'awaiting_verification' || $pendingOrder->status === 'paid' || $pendingOrder->payment_proof_path)
                                    {{-- Sudah upload bukti, menunggu admin --}}
                                    <a class="btn btn-primary"
                                        href="{{ route('checkout.success', ['slug' => $pendingOrder->package->slug, 'order' => $pendingOrder->id]) }}"
                                        style="background: #3b82f6; border-color: #3b82f6; color: white;">
                                        Lihat Status
                                    </a>
                                @else
                                    {{-- Belum bayar / Belum upload bukti --}}
                                    <a class="btn btn-primary" href="{{ route('checkout.show', $pendingOrder->package->slug) }}"
                                        style="background: #f59e0b; border-color: #f59e0b; color: white;">
                                        Lanjut Bayar
                                    </a>
                                @endif
                            @else
                                <a class="btn btn-primary" href="{{ route('packages.index') }}">
                                    Beli Paket Belajar
                                </a>
                            @endif

                            <form method="post" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-outline"
                                    style="color: #000; border-color: #ccc;">Keluar</button>
                            </form>
                        @else
                            <a class="btn btn-primary" href="{{ $joinLink }}">
                                Gabung Sekarang
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Desktop Nav Actions --}}
                <div class="nav-actions nav-actions-desktop">
                    @auth
                        {{-- LOGIKA TOMBOL DESKTOP --}}

                        @if(auth()->user()->role === 'admin')
                            <a class="btn btn-primary" href="{{ route('admin.dashboard') }}"
                                style="background: #0f766e; border-color: #0f766e; color: white; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                                Dashboard
                            </a>
                        @elseif(auth()->user()->role === 'tutor')
                            <a class="btn btn-primary" href="{{ route('tutor.dashboard') }}"
                                style="background: #0f766e; border-color: #0f766e; color: white; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Ayo Mengajar
                            </a>
                            {{-- 1. SUDAH LUNAS / AKTIF --}}
                        @elseif($hasActivePackage)
                            <a class="btn btn-primary" href="{{ route('student.dashboard') }}"
                                style="background: #0f766e; border-color: #0f766e; color: white; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Ayo Belajar
                            </a>

                            {{-- 2. ADA TRANSAKSI BERJALAN --}}
                        @elseif($pendingOrder && $pendingOrder->package)

                            @if($pendingOrder->status === 'awaiting_verification' || $pendingOrder->status === 'paid' || $pendingOrder->payment_proof_path)
                                {{-- Case: Sudah Upload Bukti (Lihat Status) --}}
                                <a class="btn btn-primary"
                                    href="{{ route('checkout.success', ['slug' => $pendingOrder->package->slug, 'order' => $pendingOrder->id]) }}"
                                    style="background: #3b82f6; border-color: #3b82f6; color: white; font-weight: 600;">
                                    Lihat Status
                                </a>
                            @else
                                {{-- Case: Belum Upload Bukti (Lanjut Bayar) --}}
                                <a class="btn btn-primary" href="{{ route('checkout.show', $pendingOrder->package->slug) }}"
                                    style="background: #f59e0b; border-color: #f59e0b; color: white; font-weight: 600;">
                                    Lanjut Bayar
                                </a>
                            @endif

                            {{-- 3. BELUM ADA TRANSAKSI (Default) --}}
                        @else
                            @if(auth()->user()->role === 'student')
                                <a class="btn btn-primary" href="{{ route('packages.index') }}">
                                    Beli Paket Belajar
                                </a>
                            @endif
                        @endif

                        <a class="nav-profile" href="{{ $profileLink ?? route('student.profile') }}"
                            aria-label="Buka profil">
                            <img src="{{ $profileAvatar }}" alt="Foto profil" />
                        </a>

                        <form method="post" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn btn-outline"
                                style="color: #000; border-color: #ccc;">Keluar</button>
                        </form>
                    @else
                        {{-- GUEST --}}
                        <a class="btn btn-primary" href="{{ route('join') }}">
                            Gabung Sekarang
                        </a>
                    @endauth

                </div>
            </div>
        </nav>
        @php
            $heroContent = $landingContents->get('hero')?->first();
            $heroImage = $heroContent?->image ?? null;

            // Gunakan AvatarResolver untuk Base64 embedding (bypass hosting URL issues)
            if ($heroImage) {
                $heroBg = \App\Support\AvatarResolver::resolve([$heroImage]);
                // Fallback ke asset jika AvatarResolver gagal
                if (!$heroBg) {
                    $heroBg = asset($heroImage);
                }
            } else {
                $heroBg = asset('images/stis_contoh.jpeg');
            }
        @endphp
        <div class="hero" id="beranda"
            style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.65)), url('{{ $heroBg }}');">
            <div class="hero-content" data-reveal data-reveal-delay="40">
                <span class="badge">{{ $heroContent->content['title'] ?? 'Bimbel MayClass' }}</span>
                <h1>{{ $heroContent->content['subtitle'] ?? 'Langkah Pasti Menuju Prestasi' }}</h1>
                <p>
                    {{ $heroContent->content['description'] ?? 'Bertemu dengan tentor terbaik MayClass dan rasakan perjalanan belajar yang terarah, fleksibel, dan penuh dukungan menuju Perguruan Tinggi impianmu.' }}
                </p>

                {{-- Hero Statistics --}}
                <div class="hero-stats" data-reveal data-reveal-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon" data-carousel="students">
                            @forelse($studentsJoined as $index => $student)
                                <img src="{{ $student['avatar'] }}" alt="{{ $student['name'] }}"
                                    class="stat-icon-photo {{ $index === 0 ? 'active' : '' }}" loading="lazy">
                            @empty
                                <span>👨‍🎓</span>
                            @endforelse
                        </div>
                        <div class="stat-number" data-target="{{ $totalStudents }}">0</div>
                        <div class="stat-label">Siswa Bergabung</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" data-carousel="tutors">
                            @forelse($activeTutors as $index => $tutor)
                                <img src="{{ $tutor['avatar'] }}" alt="{{ $tutor['name'] }}"
                                    class="stat-icon-photo {{ $index === 0 ? 'active' : '' }}" loading="lazy">
                            @empty
                                <span>👨‍🏫</span>
                            @endforelse
                        </div>
                        <div class="stat-number" data-target="{{ $totalActiveTutors }}">0</div>
                        <div class="stat-label">Tentor Aktif</div>
                    </div>
                </div>
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
                @forelse($landingContents['article'] ?? [] as $article)
                    <article class="article-card" data-reveal data-reveal-delay="{{ $loop->index * 100 }}">
                        <div class="article-image-wrapper">
                            <span class="article-badge">Tips & Trik</span>
                            <img src="{{ Str::startsWith($article->image ?? '', 'http') ? $article->image : asset($article->image ?? 'images/placeholder-article.jpg') }}"
                                alt="{{ $article->content['title'] }}"
                                onerror="this.src='https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=800&q=80'" />
                        </div>
                        <div class="article-content">

                            <h3>{{ $article->content['title'] }}</h3>
                            <p>
                                {{ Str::limit($article->content['description'], 100) }}
                            </p>
                            <div class="article-footer">
                                <a class="btn-read-more" href="{{ $article->content['link'] ?? route('packages.index') }}"
                                    target="{{ !empty($article->content['link']) ? '_blank' : '_self' }}">
                                    Baca Selengkapnya
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div
                        style="grid-column: 1 / -1; text-align: center; padding: 2rem; background: #f8fafc; border-radius: 16px;">
                        <p style="color: var(--ink-muted);">Belum ada artikel terbaru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- DOKUMENTASI SECTION - After Articles --}}
    @if($documentations->isNotEmpty())
        <section class="section" id="dokumentasi" style="background: linear-gradient(135deg, #f0fdfa 0%, #ecfdf5 100%);">
            <div class="container">
                <div class="section-header" data-reveal>
                    <h2 class="section-title">Dokumentasi Kegiatan MayClass</h2>
                    <p class="section-subtitle">
                        Intip momen seru dan kebersamaan selama belajar bersama MayClass minggu ini!
                    </p>
                </div>

                <style>
                    .doc-scroll-wrapper {
                        max-height: 520px;
                        overflow-y: auto;
                        scroll-behavior: smooth;
                        margin-top: 40px;
                        padding-right: 8px;
                    }

                    /* Scrollbar styling */
                    .doc-scroll-wrapper::-webkit-scrollbar {
                        width: 6px;
                    }

                    .doc-scroll-wrapper::-webkit-scrollbar-track {
                        background: rgba(0, 0, 0, 0.05);
                        border-radius: 3px;
                    }

                    .doc-scroll-wrapper::-webkit-scrollbar-thumb {
                        background: rgba(63, 166, 126, 0.4);
                        border-radius: 3px;
                    }

                    .doc-scroll-wrapper::-webkit-scrollbar-thumb:hover {
                        background: rgba(63, 166, 126, 0.6);
                    }

                    .doc-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                        gap: 24px;
                    }

                    @media (max-width: 768px) {
                        .doc-scroll-wrapper {
                            max-height: 420px;
                            margin-top: 20px;
                        }

                        .doc-grid {
                            display: flex;
                            flex-direction: column;
                            gap: 0;
                            scroll-snap-type: y mandatory;
                        }

                        .doc-card {
                            width: 100% !important;
                            max-width: 100% !important;
                            min-width: 0 !important;
                            flex-shrink: 0;
                            scroll-snap-align: start;
                            margin-bottom: 16px !important;
                        }

                        .doc-card>div:first-child {
                            aspect-ratio: auto !important;
                            height: 160px !important;
                        }

                        .doc-card img {
                            aspect-ratio: auto !important;
                        }

                        .doc-card .doc-desc {
                            font-size: 0.9rem !important;
                            padding: 16px !important;
                        }

                        .doc-card .doc-badge {
                            font-size: 0.6rem !important;
                            padding: 4px 8px !important;
                        }
                    }
                </style>
                <div class="doc-scroll-wrapper">
                    <div class="doc-grid">
                        @foreach($documentations as $doc)
                            <div class="doc-card" data-reveal data-reveal-delay="{{ $loop->index * 50 }}"
                                style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); transition: all 0.3s ease; cursor: pointer;"
                                onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(0, 0, 0, 0.15)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.08)'">
                                <div style="position: relative; aspect-ratio: 16/9; overflow: hidden; background: #f8fafc;">
                                            @php
                                                // Gunakan AvatarResolver untuk mengubah gambar jadi Base64
                                                // Ini mem-bypass masalah hosting/symlink/https link
                                                $docImage = \App\Support\AvatarResolver::resolve([
                                                    $doc->photo_path,
                                                    'uploads/' . $doc->photo_path
                                                ]);
                                            @endphp
                                            <img src="{{ $docImage ?? asset('storage/' . $doc->photo_path) }}"
                                                alt="Dokumentasi {{ $doc->activity_date->format('d M Y') }}"
                                                style="width: 100%; height: 100%; object-fit: cover;" loading="lazy"
                                                onerror="this.style.display='none'; this.parentElement.innerHTML='<div style=\'display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;font-size:0.9rem;\'>Gambar tidak tersedia</div>'">
                                            <div class="doc-badge"
                                                style="position: absolute; top: 12px; right: 12px; background: rgba(15, 118, 110, 0.9); backdrop-filter: blur(8px); color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                                {{ $doc->activity_date->locale('id')->translatedFormat('d M') }}
                                            </div>
                                        </div>
                                        <div class="doc-desc" style="padding: 16px;">
                                            <p style="margin: 0; color: #1e293b; font-size: 0.9rem; line-height: 1.6;">
                                                {{ Str::limit($doc->description, 100) }}
                                            </p>
                                        </div>
                                    </div>
                        @endforeach
                        </div>

                    </div>
                    <div style="text-align: center; margin-top: 32px;" data-reveal data-reveal-delay="300">
                        <p style="color: #64748b; font-size: 0.9rem; margin: 0;">
                            <strong>{{ $documentations->count() }} dokumentasi</strong> dari minggu ini • Auto-reset setiap
                            minggu!
                        </p>
                    </div>
                </div>
            </section>
    @endif

    <section class="pricing-section" id="paket">
        <div class="container">
            <div class="section-header" data-reveal>
                <h2 class="section-title">Pilih Paket Favoritmu</h2>
                <p class="section-subtitle">
                    Mulai dari SD, SMP, hingga SMA—MayClass siap menemanimu dengan sesi
                    interaktif dan laporan perkembangan rutin.
                </p>
            </div>

            <!-- Filter Dropdown -->
            <div class="filter-container" data-reveal>
                <button class="filter-btn" @click="openDropdown = !openDropdown" @click.outside="openDropdown = false">
                    <span x-text="selectedLevel"></span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        :style="openDropdown ? 'transform: rotate(180deg)' : ''" style="transition: transform 0.2s;">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </button>

                <div class="filter-dropdown" x-show="openDropdown" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    style="display: none;">
                    <div class="filter-option" :class="{ 'active': selectedLevel === 'SD' }"
                        @click="selectedLevel = 'SD'; openDropdown = false">Jenjang SD</div>
                    <div class="filter-option" :class="{ 'active': selectedLevel === 'SMP' }"
                        @click="selectedLevel = 'SMP'; openDropdown = false">Jenjang SMP</div>
                    <div class="filter-option" :class="{ 'active': selectedLevel === 'SMA' }"
                        @click="selectedLevel = 'SMA'; openDropdown = false">Jenjang SMA</div>
                </div>
            </div>

            @php
                // REGROUPING LOGIC (Menggabungkan kelas-kelas ke dalam Jenjang Besar)
                $rawCatalog = collect($landingPackages ?? []);

                // Inisialisasi wadah untuk jenjang besar
                $groupedLevels = [
                    'SD' => [
                        'label' => 'Jenjang Sekolah Dasar (SD)',
                        'desc' => 'Membangun pondasi akademik yang kuat dan menyenangkan.',
                        'items' => collect()
                    ],
                    'SMP' => [
                        'label' => 'Jenjang SMP',
                        'desc' => 'Persiapan matang untuk ujian sekolah dan penguatan materi.',
                        'items' => collect()
                    ],
                    'SMA' => [
                        'label' => 'Jenjang SMA',
                        'desc' => 'Fokus intensif menembus PTN Impian dan Sekolah Kedinasan.',
                        'items' => collect()
                    ],
                    'Lainnya' => [
                        'label' => 'Program Lainnya',
                        'desc' => 'Program pengembangan skill dan persiapan khusus.',
                        'items' => collect()
                    ]
                ];

                // Loop data mentah dan masukkan ke wadah yang sesuai
                foreach ($rawCatalog as $group) {
                    $label = strtoupper($group['stage_label'] ?? $group['stage'] ?? '');
                    $packages = collect($group['packages'] ?? []);

                    if (str_contains($label, 'SD') || str_contains($label, 'SEKOLAH DASAR')) {
                        $groupedLevels['SD']['items'] = $groupedLevels['SD']['items']->merge($packages);
                    } elseif (str_contains($label, 'SMP')) {
                        $groupedLevels['SMP']['items'] = $groupedLevels['SMP']['items']->merge($packages);
                    } elseif (str_contains($label, 'SMA') || str_contains($label, 'SMK') || str_contains($label, 'ALUMNI')) {
                        $groupedLevels['SMA']['items'] = $groupedLevels['SMA']['items']->merge($packages);
                    } else {
                        $groupedLevels['Lainnya']['items'] = $groupedLevels['Lainnya']['items']->merge($packages);
                    }
                }
            @endphp

            @php($hasAnyPackage = false)

            <div class="pricing-grid">
                @foreach ($groupedLevels as $key => $levelGroup)
                @if ($levelGroup['items']->isNotEmpty())
                @php($hasAnyPackage = true)
                @foreach ($levelGroup['items'] as $package)
                @php($features = collect($package['card_features'] ?? $package['features'] ?? [])->take(3))
                <article class="pricing-card" x-show="selectedLevel === '{{ $key }}'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100" data-reveal>
                    <span class="badge">
                        {{ $package['tag'] ?? $key }}
                    </span>
                    <strong>{{ $package['detail_title'] }}</strong>
                    <div class="pricing-price">{{ $package['card_price'] }}</div>

                    @if (!empty($package['grade_range']))
                        <div class="pricing-meta">
                            {{-- Jika isinya angka saja (misal "8"), tambahkan kata "Kelas" --}}
                            @if(is_numeric($package['grade_range']))
                                Kelas {{ $package['grade_range'] }}
                            @else
                                {{ $package['grade_range'] }}
                            @endif
                        </div>
                    @endif

                    @if ($package['summary'] ?? false)
                        <p style="margin: 0 0 12px; color: var(--ink-soft); font-size: 0.95rem;">
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
                        <button type="button" class="btn btn-primary" @click="openModal({{ json_encode($package) }})">
                            Detail Paket
                        </button>
                    </div>
                </article>
                @endforeach
                @endif
                @endforeach
            </div>

            @if (!$hasAnyPackage)
                <div style="text-align: center; padding: 3rem; background: #f8fafc; border-radius: 16px; width: 100%;">
                    <p style="color: var(--ink-muted); margin: 0;">Belum ada paket belajar yang tersedia.</p>
                </div>
            @endif
        </div>
    </section>

    <div class="highlight-section" id="keunggulan">
        <div class="highlight-content">
            <div data-reveal>
                <span class="badge" style="background: rgba(255, 255, 255, 0.16); color: #ffffff;">Mengapa
                    MayClass?</span>
                <h2 style="margin: 18px 0 12px; font-size: clamp(2.1rem, 3vw, 3rem); color: #ffffff;">Bersama MayClass
                    Belajarmu Lebih Seru</h2>
                <p style="margin: 0; max-width: 620px; color: rgba(255, 255, 255, 0.84);">
                    Rasakan pengalaman belajar intensif, hangat, dan profesional. Tim MayClass memastikan setiap sesi
                    berjalan menyenangkan dengan target capaian yang jelas.
                </p>
            </div>
            <div class="highlight-grid">
                @forelse($landingContents['feature'] ?? [] as $feature)
                    <div class="highlight-card" data-reveal data-reveal-delay="{{ $loop->index * 100 }}">
                        <strong>{{ $feature->content['title'] }}</strong>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.82);">
                            {{ $feature->content['description'] }}
                        </p>
                    </div>
                @empty
                    <div class="highlight-card" data-reveal>
                        <strong>Segera Hadir</strong>
                        <p style="margin: 0; color: rgba(255, 255, 255, 0.82);">
                            Fitur unggulan sedang disiapkan.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <section class="section testimonials" id="testimoni">
        <div class="container">
            <div class="section-header" data-reveal>
                <h2 class="section-title">Cerita Mereka yang Sudah Mewujudkan Mimpi</h2>
                <p class="section-subtitle">
                    Dengar langsung pengalaman siswa MayClass yang berhasil menembus kampus favorit dan meraih skor
                    tinggi
                    di ujian bergengsi.
                </p>
            </div>
            <!-- Swiper Testimonials -->
            <div class="swiper testimonials-slider" data-reveal>
                <div class="swiper-wrapper">
                    @forelse($landingContents['testimonial'] ?? [] as $testimonial)
                        <div class="swiper-slide">
                            <article class="testimonial-card">
                                <div class="testimonial-rating" aria-label="Rating bintang lima">
                                    <span aria-hidden="true">★</span><span aria-hidden="true">★</span><span
                                        aria-hidden="true">★</span><span aria-hidden="true">★</span><span
                                        aria-hidden="true">★</span>
                                </div>
                                <p class="testimonial-quote">
                                    “{{ $testimonial->content['quote'] }}”
                                </p>
                                <div class="testimonial-author">
                                    <div class="testimonial-avatar">
                                        <img src="{{ asset($testimonial->image ?? 'images/avatar-placeholder.svg') }}"
                                            alt="{{ $testimonial->content['name'] }}"
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($testimonial->content['name']) }}&background=random'" />
                                    </div>
                                    <div class="testimonial-meta">
                                        <strong>{{ $testimonial->content['name'] }}</strong>
                                        <span>{{ $testimonial->content['role'] }}</span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div style="text-align: center; padding: 2rem;">
                                <p style="color: var(--ink-muted);">Belum ada testimoni.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <section class="section mentor-showcase" id="tentor">
        <div class="container">
            <div class="section-header" data-reveal>
                <h2 class="section-title">Mentor Berkualitas Siap Mendampingi Belajarmu</h2>
                <p class="section-subtitle">
                    Tenaga pendidik terbaik dari berbagai kampus unggulan siap memastikan setiap sesi belajar terasa
                    dekat dan
                    menyenangkan.
                </p>
            </div>
            <!-- Swiper Mentors -->
            <div class="swiper mentor-slider" data-reveal>
                <div class="swiper-wrapper">
                    @forelse($activeTutors as $mentor)
                        <div class="swiper-slide">
                            <article class="mentor-profile">
                                <div class="mentor-avatar">
                                    <img src="{{ $mentor['avatar'] }}" alt="{{ $mentor['name'] }}"
                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($mentor['name']) }}&background=random'" />
                                </div>
                                <div class="mentor-info">
                                    <strong>{{ $mentor['name'] }}</strong>
                                    <span class="mentor-role">{{ $mentor['role'] }}</span>
                                </div>
                                <p class="mentor-saying">“{{ $mentor['quote'] }}”</p>
                                <div class="mentor-meta">
                                    @foreach($mentor['meta'] as $meta)
                                        <span>{{ $meta }}</span>
                                    @endforeach
                                </div>
                            </article>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div style="text-align: center; padding: 2rem;">
                                <p style="color: var(--ink-muted);">Belum ada data mentor.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <section class="section faq-section" id="faq">
        <div class="container">
            <div class="section-header" data-reveal>
                <h2 class="section-title">FAQ</h2>
            </div>

            <div class="faq-grid" data-reveal>
                @forelse($landingContents['faq'] ?? [] as $faq)
                    <details>
                        <summary>{{ $faq->content['question'] }}</summary>
                        <p>{{ $faq->content['answer'] }}</p>
                    </details>
                @empty
                    <div style="text-align: center; padding: 1rem;">
                        <p style="color: var(--ink-muted);">Belum ada FAQ.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- REDESIGNED FOOTER START --}}
    <style>
        /* ... existing styles ... */
        .footer-top {
            display: grid;
            grid-template-columns: 2fr 0.8fr 0.8fr 1.4fr;
            gap: 40px;
            padding-bottom: 64px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        @media (max-width: 1024px) {
            .footer-top {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }
        }

        @media (max-width: 640px) {
            .footer-top {
                grid-template-columns: 1fr;
            }
        }

        /* Footer logo styling */
        .footer-logo {
            height: 120px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .footer-logo {
                height: 90px;
            }
        }
    </style>
    <footer>
        <div class="container">
            <div class="footer-top">
                <div class="footer-brand-col">
                    <img src="{{ asset('images/Logo_MayClass.png') }}" alt="Logo MayClass" class="footer-logo" />
                    <p class="footer-desc">
                        MayClass adalah platform bimbingan belajar premium yang menggabungkan materi berkualitas, mentor
                        berpengalaman, dan teknologi terkini untuk mengantarkan siswa menuju prestasi akademik terbaik.
                    </p>

                    <div style="margin-top: 24px; display: flex; flex-direction: column; gap: 16px;">
                        <a href="https://maps.app.goo.gl/utk5opqnCtccpdCRA" target="_blank" rel="noopener noreferrer"
                            class="contact-link" style="align-items: flex-start;">
                            <svg class="contact-icon" width="20" height="20" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"
                                style="margin-top: 2px; flex-shrink: 0;">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span style="line-height: 1.5;">Jalan Kemayoran Gempol Galindra II No. 27, RT.4/RW.7, Kb.
                                Kosong, Kec. Kemayoran, Jakarta Pusat – 10630</span>
                        </a>

                        <div
                            style="width: 100%; max-width: 600px; aspect-ratio: 1 / 1; position: relative; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d494.5830393878767!2d106.85506449999999!3d-6.1562696!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f56ccd9b7d19%3A0x7da51f8f01b8c1f4!2sBimbel%20prima%20pade!5e0!3m2!1sid!2sid!4v1733126567000!5m2!1sid!2sid"
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
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
                        <!-- WHATSAPP -->
                        <div class="contact-row">
                            <a href="https://wa.me/6283194085776" target="_blank" class="contact-link">
                                <svg class="contact-icon" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 
                                             19.79 19.79 0 0 1-8.63-3.07 
                                             19.5 19.5 0 0 1-6-6 
                                             19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72 
                                             12.84 12.84 0 0 0 .7 2.81 
                                             2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 6 6l1.27-1.27
                                             a2 2 0 0 1 2.11-.45 
                                             12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>
                                <span>0831-9408-5776 (WhatsApp)</span>
                            </a>
                        </div>

                        <!-- EMAIL -->
                        <div class="contact-row">
                            <a href="mailto:mayclass.official@gmail.com" class="contact-link">
                                <svg class="contact-icon" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 
                                             1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6
                                             c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                                <span>mayclass.official@gmail.com</span>
                            </a>
                        </div>

                        <!-- JAM RESPON -->
                        <div class="contact-row">
                            <div class="contact-link">
                                <svg class="contact-icon" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>Jam respon: 09.00–21.00 WIB (Setiap hari)</span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 32px;">
                        <p style="color: var(--footer-text); font-size: 0.9rem; margin-bottom: 12px;">Pembayaran dapat
                            dilakukan melalui:</p>
                        <div
                            style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; align-items: center;">
                            <!-- Banks -->
                            <div class="payment-icon"
                                style="background: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; height: 40px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg"
                                    alt="BCA" style="max-height: 100%; max-width: 100%;">
                            </div>
                            <div class="payment-icon"
                                style="background: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; height: 40px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg"
                                    alt="Mandiri" style="max-height: 100%; max-width: 100%;">
                            </div>
                            <div class="payment-icon"
                                style="background: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; height: 40px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/BANK_BRI_logo.svg"
                                    alt="BRI" style="max-height: 100%; max-width: 100%;">
                            </div>
                            <div class="payment-icon"
                                style="background: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; height: 40px;">
                                <img src="https://www.theswiftcodes.com/images/bank-logo/generic/bank-negara-indonesia.png"
                                    alt="SeaBank" style="max-height: 100%; max-width: 100%;">
                            </div>

                            <!-- E-Wallets -->
                            <div class="payment-icon"
                                style="background: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; height: 40px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg"
                                    alt="GoPay" style="max-height: 100%; max-width: 100%;">
                            </div>
                            <div class="payment-icon"
                                style="background: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; height: 40px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg"
                                    alt="OVO" style="max-height: 100%; max-width: 100%;">
                            </div>
                            <div class="payment-icon"
                                style="background: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; height: 40px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Shopee.svg"
                                    alt="ShopeePay" style="max-height: 100%; max-width: 100%;">
                            </div>
                            <div class="payment-icon"
                                style="background: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; height: 40px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg"
                                    alt="Dana" style="max-height: 100%; max-width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2025 MayClass Education. All rights reserved.</p>
            </div>
        </div>
    </footer>
    {{-- REDESIGNED FOOTER END --}}

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Testimonials Swiper
            const testimonialSwiper = new Swiper('.testimonials-slider', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                speed: 800,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
            });

            // Initialize Mentor Swiper
            const mentorSwiper = new Swiper('.mentor-slider', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                speed: 800,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'slide',
            });
            const root = document.documentElement;

            if (!root || root.dataset.page !== 'landing') {
                return;
            }

            // Hamburger menu functionality
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');

            if (hamburger && navLinks) {
                const toggleMenu = () => {
                    const isActive = hamburger.classList.toggle('active');
                    navLinks.classList.toggle('active');
                    hamburger.setAttribute('aria-expanded', isActive);

                    // Prevent body scroll when menu is open
                    if (isActive) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                };

                hamburger.addEventListener('click', toggleMenu);

                // Close menu when clicking on a link
                navLinks.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        if (navLinks.classList.contains('active')) {
                            toggleMenu();
                        }
                    });
                });

                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                    if (navLinks.classList.contains('active') &&
                        !navLinks.contains(e.target) &&
                        !hamburger.contains(e.target)) {
                        toggleMenu();
                    }
                });

                // Close menu on escape key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && navLinks.classList.contains('active')) {
                        toggleMenu();
                    }
                });
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

            // Count-up animation for statistics
            const statNumbers = document.querySelectorAll('.stat-number');
            const animateCountUp = (element) => {
                const target = parseInt(element.getAttribute('data-target'));
                const duration = 2000; // 2 seconds
                const increment = target / (duration / 16); // 60fps
                let current = 0;

                const updateCount = () => {
                    current += increment;
                    if (current < target) {
                        element.textContent = Math.floor(current);
                        requestAnimationFrame(updateCount);
                    } else {
                        element.textContent = target;
                    }
                };

                updateCount();
            };

            // Observe stat numbers for animation trigger
            const statObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                        entry.target.classList.add('counted');
                        animateCountUp(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });

            statNumbers.forEach(stat => {
                statObserver.observe(stat);
            });

            // Profile Photo Carousel Switching
            const iconCarousels = document.querySelectorAll('[data-carousel]');

            iconCarousels.forEach(carousel => {
                const photos = carousel.querySelectorAll('.stat-icon-photo');

                if (photos.length > 1) {
                    let currentIndex = 0;

                    setInterval(() => {
                        // Hide current photo
                        photos[currentIndex].classList.remove('active');

                        // Move to next photo
                        currentIndex = (currentIndex + 1) % photos.length;

                        // Show next photo
                        photos[currentIndex].classList.add('active');
                    }, 3000); // Switchevery 3 seconds
                }
            });
        });
    </script>

    <!-- Package Detail Modal -->
    <div class="modal-overlay" x-show="modalOpen" x-transition.opacity x-cloak @click.self="closeModal()">
        <div class="modal-container">
            <button @click="closeModal()" class="modal-close">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <div class="modal-body">

                <div class="modal-content-grid">
                    <!-- LEFT COLUMN -->
                    <div>
                        <!-- Badge Program -->
                        <span class="badge-tag"
                            style="position: static; display: inline-block; margin-bottom: 16px; background: #ccfbf1; color: #0f766e; font-size: 0.8rem; padding: 6px 12px; border-radius: 99px; font-weight: 700;"
                            x-show="pkg.tag" x-text="pkg.tag ?? 'PROGRAM'"></span>

                        <!-- Title -->
                        <h2 class="modal-title" x-text="pkg.detail_title"></h2>

                        <!-- Description -->
                        <div style="margin-bottom: 24px; color: var(--text-muted); line-height: 1.6; font-size: 1rem;"
                            x-text="pkg.description"></div>

                        <!-- Badges Row -->
                        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 32px;">
                            <div
                                style="display: flex; align-items: center; gap: 8px; background: #f1f5f9; padding: 8px 12px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; color: var(--text-muted);">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span x-text="pkg.stage_label || pkg.stage"></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; background: #f0f9ff; color: #0369a1; padding: 8px 12px; border-radius: 8px; font-size: 0.9rem; font-weight: 600;"
                                x-show="pkg.grade_range">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                <span x-text="pkg.grade_range"></span>
                            </div>
                            <div
                                style="display: flex; align-items: center; gap: 8px; background: #f0fdf4; color: #15803d; padding: 8px 12px; border-radius: 8px; font-size: 0.9rem; font-weight: 600;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span x-text="'Kuota ' + (pkg.quota_limit || '∞') + ' siswa'"></span>
                            </div>
                        </div>

                        <!-- Subjects Section -->
                        <div style="border-top: 1px solid var(--border); padding-top: 24px;">
                            <h4 style="margin: 0 0 16px; font-size: 1.1rem; color: var(--text-main);">Mata Pelajaran
                            </h4>

                            <div style="display: grid; gap: 12px;">
                                <template x-for="subject in (pkg.subjects || [])">
                                    <div
                                        style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                        <div
                                            style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                            📚
                                        </div>
                                        <div style="font-weight: 600; color: var(--text-main);" x-text="subject.name">
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!pkg.subjects || pkg.subjects.length === 0"
                                    style="color: var(--text-muted); font-style: italic;">
                                    Tidak ada data mata pelajaran.
                                </div>
                            </div>
                        </div>

                        <!-- Features Section (Moved to Left) -->
                        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                            <h4 style="margin: 0 0 16px; font-size: 1.1rem; color: var(--text-main);">Fasilitas
                                Termasuk:</h4>
                            <ul
                                style="font-size: 0.95rem; color: var(--text-muted); padding: 0; list-style: none; display: grid; gap: 10px;">
                                <template x-for="feature in (pkg.features || [])">
                                    <li style="display: flex; gap: 10px; align-items: start;">
                                        <div style="color: var(--primary); margin-top: 2px;">
                                            <svg width="18" height="18" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <span x-text="feature" style="line-height: 1.5;"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN (Sticky Price Card) -->
                    <div
                        style="background: white; border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow-md);">
                        <div
                            style="background: #ecfdf5; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 16px;">
                            <div
                                style="font-size: 0.75rem; font-weight: 700; color: #047857; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                                Harga</div>
                            <div style="font-size: 1.75rem; font-weight: 800; color: #065f46; line-height: 1;"
                                x-text="pkg.card_price"></div>
                            <div style="font-size: 0.9rem; color: #047857; margin-top: 4px;">per bulan</div>
                        </div>

                        <div
                            style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 10px; border-radius: 8px; text-align: center; font-size: 0.9rem; font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span
                                x-text="'Slot Tersedia (' + (pkg.quota_remaining !== undefined ? Math.max(0, pkg.quota_remaining) : '-') + '/' + (pkg.quota_limit || '-') + ')'"></span>
                        </div>

                        <a :href="'/checkout/' + pkg.slug" class="btn btn-primary btn-block"
                            style="padding: 14px; font-size: 1rem; border-radius: 10px; width: 100%; display: flex; justify-content: center;">
                            Checkout Sekarang
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ========================================
    SIMPLE CLEAN GREETING POPUP
    Pop-up sapaan minimalis ramah anak
    ======================================== --}}
    <style>
        /* Simple popup */
        .simple-greeting {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #1b6d4f;
            color: white;
            padding: 48px 64px;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(27, 109, 79, 0.4);
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
            text-align: center;
            min-width: 400px;
        }

        .simple-greeting.show {
            opacity: 1;
        }

        .simple-greeting h2 {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin: 0 0 12px 0;
            letter-spacing: 0.5px;
        }

        .simple-greeting p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            line-height: 1.5;
        }

        @media (max-width: 640px) {
            .simple-greeting {
                padding: 36px 40px;
                width: 88%;
                min-width: auto;
                max-width: 380px;
            }

            .simple-greeting h2 {
                font-size: 26px;
            }

            .simple-greeting p {
                font-size: 16px;
            }
        }
    </style>

    {{-- Popup --}}
    <div class="simple-greeting" id="simpleGreeting">
        <h2 id="greetingTitle">Selamat Datang!</h2>
        <p>Mari explore keseruan di MayClass! ✨</p>
    </div>

    <script>
        // Simple greeting popup
        (function () {
            const popup = document.getElementById('simpleGreeting');
            const titleEl = document.getElementById('greetingTitle');

            function getTimeBasedGreeting() {
                const hour = new Date().getHours();
                if (hour >= 5 && hour < 11) return 'Selamat Pagi!';
                if (hour >= 11 && hour < 15) return 'Selamat Siang!';
                if (hour >= 15 && hour < 18) return 'Selamat Sore!';
                return 'Selamat Malam!';
            }

            function showGreeting() {
                titleEl.textContent = getTimeBasedGreeting();

                // Show after 300ms delay
                setTimeout(() => {
                    popup.classList.add('show');
                }, 500);

                // Hide after 3.5 seconds (500ms delay + 3000ms display)
                setTimeout(() => {
                    popup.classList.remove('show');
                }, 3000);
            }

            window.addEventListener('load', () => {
                showGreeting();
            });
        })();
    </script>

    {{-- End of Greeting Popup --}}


</body>

</html>