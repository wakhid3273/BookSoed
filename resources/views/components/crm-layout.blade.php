<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BookSoed — CRM' }} | BookSoed</title>
    <meta name="description" content="BookSoed — Platform Jual Beli Buku Pre-Loved Civitas Akademika Unsoed">

    {{-- Google Fonts: Cormorant Garamond (Art Nouveau) + Inter (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ============================================================
           BOOKSOED CRM — Art Nouveau Dark Mode + Liquid Glass System
           Color Palette:
             --petal:    #F6D83A  Brilliant Yellow (petals)
             --stamen:   #DDB911  Vivid Yellow (stamen/gold)
             --leaf:     #5C9550  Lush Pad Green
             --olive:    #4B7416  Olive/Mottled Green
             --plum:     #90456e  Bronzy Red/Plum
             --water:    #86C3C9  Pond Water Blue/Cyan
             --deep:     #0C1410  Deep forest dark
        ============================================================ */

        :root {
            --petal:        #F6D83A;
            --stamen:       #DDB911;
            --leaf:         #5C9550;
            --olive:        #4B7416;
            --plum:         #90456e;
            --water:        #86C3C9;
            --deep:         #0C1410;
            --deep-mid:     #111a13;
            --deep-surface: #172018;
            --text-primary: #e8f0e9;
            --text-muted:   #8aab8c;
            --gold-glow:    rgba(246, 216, 58, 0.18);
            --water-glow:   rgba(134, 195, 201, 0.15);
            --glass-bg:     rgba(20, 33, 22, 0.60);
            --glass-border: rgba(246, 216, 58, 0.20);
            --glass-shine:  rgba(255, 255, 255, 0.06);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            background-color: var(--deep);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-weight: 300;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Animated Background ──────────────────────────────────── */
        .crm-bg {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 10%, rgba(92,149,80,0.11) 0%, transparent 65%),
                radial-gradient(ellipse 55% 45% at 85% 85%, rgba(134,195,201,0.09) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 50% 50%, rgba(75,116,22,0.06) 0%, transparent 70%),
                linear-gradient(160deg, #0C1410 0%, #0e1b10 45%, #0b1714 100%);
            pointer-events: none;
        }
        .crm-bg::before {
            content: '';
            position: absolute; top: -20%; left: -10%;
            width: 65vw; height: 65vh;
            background: radial-gradient(ellipse, rgba(92,149,80,0.06) 0%, transparent 70%);
            animation: float-blob 20s ease-in-out infinite alternate;
            border-radius: 50%;
        }
        .crm-bg::after {
            content: '';
            position: absolute; bottom: -10%; right: -10%;
            width: 55vw; height: 55vh;
            background: radial-gradient(ellipse, rgba(134,195,201,0.06) 0%, transparent 70%);
            animation: float-blob 25s ease-in-out infinite alternate-reverse;
            border-radius: 50%;
        }
        @keyframes float-blob {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(4%, 7%) scale(1.07); }
        }

        /* ── Art Nouveau Corner Ornaments ─────────────────────────── */
        .nouveau-corner {
            position: fixed; z-index: 1;
            pointer-events: none; opacity: 0.30;
        }
        .nouveau-corner.tl { top: 0; left: 0; }
        .nouveau-corner.tr { top: 0; right: 0; transform: scaleX(-1); }
        .nouveau-corner.bl { bottom: 0; left: 0; transform: scaleY(-1); }
        .nouveau-corner.br { bottom: 0; right: 0; transform: scale(-1,-1); }

        /* ── Layout Wrapper ───────────────────────────────────────── */
        .crm-wrapper {
            position: relative; z-index: 2;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── Liquid Glass Navbar ──────────────────────────────────── */
        .crm-nav {
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 64px;
            backdrop-filter: blur(28px) saturate(1.8);
            -webkit-backdrop-filter: blur(28px) saturate(1.8);
            background: rgba(10, 18, 12, 0.78);
            border-bottom: 1px solid rgba(246, 216, 58, 0.14);
            box-shadow:
                0 1px 0 rgba(255,255,255,0.035) inset,
                0 8px 32px rgba(0,0,0,0.45);
        }

        .crm-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem; font-weight: 700; font-style: italic;
            letter-spacing: 0.02em;
            color: var(--petal);
            text-decoration: none;
            text-shadow: 0 0 20px rgba(246,216,58,0.38);
            display: flex; align-items: center; gap: 0.5rem;
            transition: text-shadow 0.3s;
        }
        .crm-logo:hover { text-shadow: 0 0 30px rgba(246,216,58,0.55); }

        .crm-nav-links {
            display: flex; align-items: center; gap: 0.2rem;
        }

        .crm-nav-link {
            padding: 0.4rem 1.1rem;
            border-radius: 999px;
            font-size: 0.85rem; font-weight: 400;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s, background 0.2s;
        }
        .crm-nav-link:hover { color: var(--petal); background: rgba(246,216,58,0.08); }
        .crm-nav-link.active { color: var(--petal); background: rgba(246,216,58,0.12); }

        .crm-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            border: 1.5px solid rgba(92,149,80,0.55);
            background: linear-gradient(135deg, var(--olive), var(--leaf));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem; font-weight: 700; color: var(--petal);
            box-shadow: 0 0 10px rgba(92,149,80,0.25);
        }

        /* ── Liquid Glass Card ────────────────────────────────────── */
        .lg-card {
            position: relative;
            background: var(--glass-bg);
            backdrop-filter: blur(22px) saturate(1.5);
            -webkit-backdrop-filter: blur(22px) saturate(1.5);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .lg-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(246,216,58,0.32), transparent);
        }
        .lg-card::after {
            content: '';
            position: absolute; top: 0; left: 0; width: 100%; height: 38%;
            background: linear-gradient(180deg, rgba(255,255,255,0.055) 0%, transparent 100%);
            pointer-events: none;
        }
        .lg-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 44px rgba(0,0,0,0.52), 0 0 0 1px rgba(246,216,58,0.22);
            border-color: rgba(246,216,58,0.32);
        }

        /* ── Liquid Glass Buttons ─────────────────────────────────── */
        .lg-btn {
            position: relative;
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.55rem 1.4rem;
            border-radius: 999px;
            font-size: 0.875rem; font-weight: 500;
            cursor: pointer; border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.25s ease;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            overflow: hidden; outline: none;
        }
        .lg-btn::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 50%;
            background: linear-gradient(180deg, rgba(255,255,255,0.09) 0%, transparent 100%);
            border-radius: 999px 999px 0 0; pointer-events: none;
        }

        .lg-btn-primary {
            background: rgba(246,216,58,0.14);
            border-color: rgba(246,216,58,0.40);
            color: var(--petal);
            box-shadow: 0 0 14px rgba(246,216,58,0.16), inset 0 1px 0 rgba(255,255,255,0.09);
        }
        .lg-btn-primary:hover {
            background: rgba(246,216,58,0.24);
            box-shadow: 0 0 26px rgba(246,216,58,0.28);
            transform: translateY(-1px); color: var(--petal);
        }
        .lg-btn-water {
            background: rgba(134,195,201,0.12);
            border-color: rgba(134,195,201,0.35);
            color: var(--water);
            box-shadow: 0 0 14px rgba(134,195,201,0.13);
        }
        .lg-btn-water:hover {
            background: rgba(134,195,201,0.20);
            box-shadow: 0 0 22px rgba(134,195,201,0.24);
            transform: translateY(-1px); color: var(--water);
        }
        .lg-btn-plum {
            background: rgba(144,69,110,0.14);
            border-color: rgba(144,69,110,0.38);
            color: #c98ab8;
            box-shadow: 0 0 14px rgba(144,69,110,0.13);
        }
        .lg-btn-plum:hover {
            background: rgba(144,69,110,0.24);
            box-shadow: 0 0 22px rgba(144,69,110,0.24);
            transform: translateY(-1px); color: #d9a0cc;
        }
        .lg-btn-ghost {
            background: rgba(255,255,255,0.04);
            border-color: rgba(255,255,255,0.10);
            color: var(--text-muted);
        }
        .lg-btn-ghost:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.18);
            color: var(--text-primary);
        }

        /* ── Liquid Glass Pills / Badges ──────────────────────────── */
        .lg-pill {
            display: inline-flex; align-items: center;
            padding: 0.18rem 0.72rem;
            border-radius: 999px;
            font-size: 0.72rem; font-weight: 500;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid; line-height: 1.4;
        }
        .lg-pill-petal  { background: rgba(246,216,58,0.12); border-color: rgba(246,216,58,0.30); color: var(--petal); }
        .lg-pill-leaf   { background: rgba(92,149,80,0.14);  border-color: rgba(92,149,80,0.34);  color: #8fcf80; }
        .lg-pill-water  { background: rgba(134,195,201,0.12);border-color: rgba(134,195,201,0.30);color: var(--water); }
        .lg-pill-plum   { background: rgba(144,69,110,0.14); border-color: rgba(144,69,110,0.34); color: #c98ab8; }
        .lg-pill-muted  { background: rgba(255,255,255,0.04);border-color: rgba(255,255,255,0.09);color: var(--text-muted); }

        /* ── Liquid Glass Input ───────────────────────────────────── */
        .lg-input {
            width: 100%;
            background: rgba(16, 26, 18, 0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(246,216,58,0.18);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            color: var(--text-primary);
            font-size: 0.9rem; font-family: 'Inter', sans-serif;
            transition: border-color 0.25s, box-shadow 0.25s;
            outline: none;
        }
        .lg-input:focus {
            border-color: rgba(246,216,58,0.45);
            box-shadow: 0 0 0 3px rgba(246,216,58,0.07), 0 0 18px rgba(246,216,58,0.09);
        }
        .lg-input::placeholder { color: rgba(138,171,140,0.45); }

        /* ── Typography ───────────────────────────────────────────── */
        .nouveau-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem; font-weight: 700; font-style: italic;
            color: var(--petal);
            text-shadow: 0 0 22px rgba(246,216,58,0.22);
            letter-spacing: 0.01em;
            position: relative;
            display: inline-block;
        }
        .nouveau-heading::after {
            content: '';
            position: absolute; bottom: -6px; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, var(--stamen), transparent);
        }
        .nouveau-subheading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem; font-weight: 600;
            color: var(--water);
            letter-spacing: 0.02em;
        }

        /* ── Stat Card ────────────────────────────────────────────── */
        .stat-card { text-align: center; padding: 1.5rem 1rem; }
        .stat-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem; font-weight: 700; line-height: 1;
        }
        .stat-label {
            font-size: 0.72rem; color: var(--text-muted);
            margin-top: 0.3rem; text-transform: uppercase; letter-spacing: 0.08em;
        }

        /* ── Star Rating ──────────────────────────────────────────── */
        .star-rating { display: flex; gap: 0.3rem; }
        .star {
            font-size: 1.4rem; cursor: pointer;
            color: rgba(255,255,255,0.12);
            transition: color 0.15s, transform 0.15s;
            line-height: 1; background: none; border: none;
        }
        .star.filled { color: var(--petal); }
        .star:hover  { transform: scale(1.2); }

        /* ── Wishlist Heart ───────────────────────────────────────── */
        .wishlist-heart {
            position: absolute; top: 9px; right: 9px; z-index: 5;
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            backdrop-filter: blur(14px);
            background: rgba(16, 26, 18, 0.70);
            border: 1px solid rgba(144,69,110,0.28);
            cursor: pointer; transition: all 0.25s ease;
            outline: none;
        }
        .wishlist-heart:hover { background: rgba(144,69,110,0.20); border-color: rgba(144,69,110,0.55); transform: scale(1.1); }
        .wishlist-heart.active { background: rgba(144,69,110,0.28); border-color: rgba(144,69,110,0.60); }

        /* ── Book Cover ───────────────────────────────────────────── */
        .book-cover { width:100%; aspect-ratio:3/4; object-fit:cover; border-radius:8px 8px 0 0; display:block; }
        .book-cover-placeholder {
            width:100%; aspect-ratio:3/4;
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            background: linear-gradient(135deg, rgba(75,116,22,0.40), rgba(12,20,16,0.85));
            border-radius:8px 8px 0 0; gap:0.5rem;
        }

        /* ── Order Row ────────────────────────────────────────────── */
        .order-row {
            display:flex; align-items:center; gap:1rem;
            padding: 0.8rem 1rem; border-radius:12px;
            transition: background 0.2s;
        }
        .order-row:hover { background: rgba(246,216,58,0.04); }

        /* ── Flash Alert ──────────────────────────────────────────── */
        .crm-alert {
            padding: 0.75rem 1.25rem; border-radius: 10px;
            font-size: 0.875rem; backdrop-filter: blur(10px);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .crm-alert-success {
            background: rgba(92,149,80,0.15);
            border: 1px solid rgba(92,149,80,0.35); color: #8fcf80;
        }
        .crm-alert-error {
            background: rgba(144,69,110,0.15);
            border: 1px solid rgba(144,69,110,0.35); color: #c98ab8;
        }

        /* ── Shimmer / Animation ──────────────────────────────────── */
        @keyframes shimmer-gold {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        .gold-shimmer {
            background: linear-gradient(90deg, var(--stamen), var(--petal), #fff8c0, var(--petal), var(--stamen));
            background-size: 200% auto;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer-gold 4s linear infinite;
        }
        @keyframes fade-up {
            from { opacity:0; transform:translateY(14px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fade-up 0.4s ease forwards; }

        /* ── Utility ──────────────────────────────────────────────── */
        .text-petal  { color: var(--petal); }
        .text-stamen { color: var(--stamen); }
        .text-leaf   { color: #8fcf80; }
        .text-water  { color: var(--water); }
        .text-plum   { color: #c98ab8; }
        .text-muted  { color: var(--text-muted); }

        .crm-main { flex: 1; padding: 2rem 1.5rem; }
        .crm-container { max-width: 1200px; margin: 0 auto; }

        @media (max-width: 768px) {
            .crm-nav { padding: 0 1rem; }
            .crm-main { padding: 1.25rem 1rem; }
            .crm-nav-links { display: none; }
        }
    </style>
</head>

<body>
    {{-- Animated Dark BG --}}
    <div class="crm-bg" aria-hidden="true"></div>

    {{-- Art Nouveau SVG Corner Ornaments --}}
    <svg class="nouveau-corner tl" width="180" height="180" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M12 168 C12 88, 60 22, 168 12" stroke="#F6D83A" stroke-width="1.2" fill="none"/>
        <path d="M12 140 C12 72, 46 32, 130 12" stroke="#5C9550" stroke-width="0.9" fill="none"/>
        <path d="M12 108 C12 58, 36 30, 88 12" stroke="#86C3C9" stroke-width="0.7" fill="none"/>
        <ellipse cx="20" cy="20" rx="8" ry="14" fill="#F6D83A" fill-opacity="0.22" transform="rotate(-32 20 20)"/>
        <ellipse cx="36" cy="13" rx="6" ry="10" fill="#5C9550" fill-opacity="0.18" transform="rotate(-58 36 13)"/>
        <circle cx="16" cy="16" r="3.5" fill="#DDB911" fill-opacity="0.45"/>
        <path d="M8 8 C22 18, 18 42, 8 54 C18 48, 42 46, 54 28" stroke="#5C9550" stroke-width="0.9" fill="none"/>
        <path d="M18 5 C36 22, 24 48, 14 64" stroke="#F6D83A" stroke-width="0.8" fill="none"/>
        <path d="M42 9 C46 22, 36 30, 26 24 C36 22, 43 16, 42 9Z" fill="#4B7416" fill-opacity="0.28"/>
        <path d="M62 7 C68 22, 56 32, 45 24 C56 22, 64 15, 62 7Z" fill="#5C9550" fill-opacity="0.22"/>
        <circle cx="55" cy="18" r="2" fill="#86C3C9" fill-opacity="0.35"/>
        <circle cx="28" cy="40" r="1.5" fill="#90456e" fill-opacity="0.30"/>
    </svg>

    <svg class="nouveau-corner tr" width="180" height="180" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M12 168 C12 88, 60 22, 168 12" stroke="#F6D83A" stroke-width="1.2" fill="none"/>
        <path d="M12 140 C12 72, 46 32, 130 12" stroke="#5C9550" stroke-width="0.9" fill="none"/>
        <path d="M12 108 C12 58, 36 30, 88 12" stroke="#86C3C9" stroke-width="0.7" fill="none"/>
        <ellipse cx="20" cy="20" rx="8" ry="14" fill="#F6D83A" fill-opacity="0.22" transform="rotate(-32 20 20)"/>
        <circle cx="16" cy="16" r="3.5" fill="#DDB911" fill-opacity="0.45"/>
        <path d="M42 9 C46 22, 36 30, 26 24 C36 22, 43 16, 42 9Z" fill="#4B7416" fill-opacity="0.28"/>
    </svg>

    <svg class="nouveau-corner bl" width="130" height="130" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M8 8 C8 68, 48 112, 122 122" stroke="#90456e" stroke-width="0.9" fill="none"/>
        <path d="M8 28 C8 72, 40 108, 108 122" stroke="#86C3C9" stroke-width="0.7" fill="none"/>
        <ellipse cx="20" cy="20" rx="7" ry="12" fill="#90456e" fill-opacity="0.18" transform="rotate(22 20 20)"/>
        <circle cx="30" cy="45" r="2" fill="#86C3C9" fill-opacity="0.30"/>
    </svg>

    <svg class="nouveau-corner br" width="130" height="130" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M8 8 C8 68, 48 112, 122 122" stroke="#90456e" stroke-width="0.9" fill="none"/>
        <path d="M8 28 C8 72, 40 108, 108 122" stroke="#86C3C9" stroke-width="0.7" fill="none"/>
        <ellipse cx="20" cy="20" rx="7" ry="12" fill="#90456e" fill-opacity="0.18" transform="rotate(22 20 20)"/>
        <circle cx="35" cy="50" r="2" fill="#F6D83A" fill-opacity="0.25"/>
    </svg>

    {{-- Main App Shell --}}
    <div class="crm-wrapper">

        {{-- ── Liquid Glass Navbar ──────────────────────────────── --}}
        <nav class="crm-nav" role="navigation" aria-label="CRM Navigation" id="crm-navbar">
            <a href="{{ route('home') }}" class="crm-logo" id="crm-logo-link">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M3 17V3h9l4 4v10H3z" stroke="#F6D83A" stroke-width="1.2" fill="none"/>
                    <path d="M12 3v4h4" stroke="#F6D83A" stroke-width="1.2" fill="none"/>
                    <path d="M6 9h8M6 12h6M6 15h4" stroke="#DDB911" stroke-width="1" stroke-opacity="0.8" stroke-linecap="round"/>
                </svg>
                BookSoed
            </a>

            <div class="crm-nav-links" role="list">
                <a href="{{ route('home') }}"
                   class="crm-nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                   id="nav-link-home">Katalog</a>
                
                @auth
                    <a href="{{ route('crm.dashboard') }}"
                       class="crm-nav-link {{ request()->routeIs('crm.dashboard') ? 'active' : '' }}"
                       id="nav-link-profil">Profil</a>
                    <a href="{{ route('crm.wishlist') }}"
                       class="crm-nav-link {{ request()->routeIs('crm.wishlist') ? 'active' : '' }}"
                       id="nav-link-wishlist">Wishlist</a>
                @else
                    <a href="{{ route('login') }}" class="crm-nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Masuk</a>
                    <a href="{{ route('register') }}" class="crm-nav-link {{ request()->routeIs('register') ? 'active' : '' }}">Daftar</a>
                @endauth
            </div>

            <div style="display:flex; align-items:center; gap:0.75rem;">
                @auth
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="lg-btn lg-btn-ghost" style="padding: 0.3rem 0.8rem; font-size: 0.75rem;">Keluar</button>
                    </form>
                    <div class="crm-avatar" id="crm-nav-avatar" title="{{ Auth::user()->email }}">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endauth
            </div>
        </nav>

        {{-- ── Flash Message ────────────────────────────────────── --}}
        @if(session('crm_status') || session('wishlist_status'))
            <div style="max-width:1200px; margin: 1rem auto; padding: 0 1.5rem;" id="crm-flash-wrap">
                <div class="crm-alert crm-alert-success fade-up" role="alert" id="crm-flash-alert">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="8" r="7" stroke="#8fcf80" stroke-width="1.2"/>
                        <path d="M5 8l2 2 4-4" stroke="#8fcf80" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    {{ session('crm_status') ?? session('wishlist_status') }}
                </div>
            </div>
        @endif

        {{-- ── Page Content ─────────────────────────────────────── --}}
        <main class="crm-main" id="crm-main-content">
            <div class="crm-container fade-up">
                {{ $slot }}
            </div>
        </main>

        {{-- ── Footer ───────────────────────────────────────────── --}}
        <footer style="text-align:center; padding:1.25rem; opacity:0.25; font-size:0.68rem; color:var(--text-muted); font-family:'Cormorant Garamond',serif; font-style:italic;" role="contentinfo">
            BookSoed &mdash; Civitas Akademika Universitas Jenderal Soedirman
        </footer>
    </div>

    <script>
        // Auto-dismiss flash alert after 4s
        const flashAlert = document.getElementById('crm-flash-alert');
        if (flashAlert) {
            setTimeout(() => {
                flashAlert.style.transition = 'opacity 0.5s';
                flashAlert.style.opacity = '0';
                setTimeout(() => flashAlert.closest('#crm-flash-wrap')?.remove(), 500);
            }, 4000);
        }
    </script>

    @stack('scripts')
</body>
</html>
