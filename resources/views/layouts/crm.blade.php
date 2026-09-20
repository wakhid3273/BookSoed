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

        * { box-sizing: border-box; }

        html, body {
            margin: 0; padding: 0;
            background-color: var(--deep);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            font-weight: 300;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Background ─────────────────────────────────────────── */
        .crm-bg {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(92, 149, 80, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 90%, rgba(134, 195, 201, 0.10) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 50% 50%, rgba(75, 116, 22, 0.07) 0%, transparent 70%),
                linear-gradient(160deg, #0C1410 0%, #0e1b10 40%, #0b1714 100%);
        }

        /* Subtle animated ambient blobs */
        .crm-bg::before {
            content: '';
            position: absolute; top: -20%; left: -10%;
            width: 60vw; height: 60vh;
            background: radial-gradient(ellipse, rgba(92,149,80,0.07) 0%, transparent 70%);
            animation: float-blob 18s ease-in-out infinite alternate;
            border-radius: 50%;
        }
        .crm-bg::after {
            content: '';
            position: absolute; bottom: -10%; right: -10%;
            width: 50vw; height: 50vh;
            background: radial-gradient(ellipse, rgba(134,195,201,0.07) 0%, transparent 70%);
            animation: float-blob 22s ease-in-out infinite alternate-reverse;
            border-radius: 50%;
        }

        @keyframes float-blob {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(5%, 8%) scale(1.08); }
        }

        /* ── Art Nouveau SVG Ornaments ───────────────────────────── */
        .nouveau-corner {
            position: fixed; z-index: 1;
            pointer-events: none; opacity: 0.35;
        }
        .nouveau-corner.top-left  { top: 0; left: 0; }
        .nouveau-corner.top-right { top: 0; right: 0; transform: scaleX(-1); }
        .nouveau-corner.bot-left  { bottom: 0; left: 0; transform: scaleY(-1); }
        .nouveau-corner.bot-right { bottom: 0; right: 0; transform: scale(-1,-1); }

        /* ── Layout ──────────────────────────────────────────────── */
        .crm-wrapper {
            position: relative; z-index: 2;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── Liquid Glass Navbar ─────────────────────────────────── */
        .crm-nav {
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 64px;
            backdrop-filter: blur(24px) saturate(1.6);
            -webkit-backdrop-filter: blur(24px) saturate(1.6);
            background: rgba(12, 20, 16, 0.72);
            border-bottom: 1px solid rgba(246, 216, 58, 0.15);
            box-shadow:
                0 1px 0 rgba(255,255,255,0.04) inset,
                0 8px 32px rgba(0,0,0,0.4);
        }

        .crm-nav-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem; font-weight: 700;
            font-style: italic;
            letter-spacing: 0.02em;
            color: var(--petal);
            text-decoration: none;
            text-shadow: 0 0 18px rgba(246,216,58,0.4);
            display: flex; align-items: center; gap: 0.5rem;
        }

        .crm-nav-links {
            display: flex; align-items: center; gap: 0.25rem;
        }

        .crm-nav-link {
            position: relative;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            font-size: 0.85rem; font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.25s ease, background 0.25s ease;
        }
        .crm-nav-link:hover,
        .crm-nav-link.active {
            color: var(--petal);
            background: rgba(246,216,58,0.10);
        }

        .crm-nav-user {
            display: flex; align-items: center; gap: 0.75rem;
        }

        .crm-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: 1.5px solid var(--leaf);
            background: linear-gradient(135deg, var(--olive), var(--leaf));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem; font-weight: 700;
            color: var(--petal);
            cursor: pointer;
        }

        /* ── Liquid Glass Card ───────────────────────────────────── */
        .lg-card {
            position: relative;
            background: var(--glass-bg);
            backdrop-filter: blur(20px) saturate(1.4);
            -webkit-backdrop-filter: blur(20px) saturate(1.4);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .lg-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(246,216,58,0.35), transparent);
        }
        .lg-card::after {
            content: '';
            position: absolute; top: 0; left: 0;
            width: 100%; height: 40%;
            background: linear-gradient(180deg, var(--glass-shine) 0%, transparent 100%);
            pointer-events: none;
        }
        .lg-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.5), 0 0 0 1px rgba(246,216,58,0.25);
            border-color: rgba(246,216,58,0.35);
        }

        /* ── Liquid Glass Button ─────────────────────────────────── */
        .lg-btn {
            position: relative;
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.55rem 1.4rem;
            border-radius: 999px;
            font-size: 0.875rem; font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.25s ease;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            overflow: hidden;
        }
        .lg-btn::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 50%;
            background: linear-gradient(180deg, rgba(255,255,255,0.10) 0%, transparent 100%);
            border-radius: 999px 999px 0 0;
        }
        .lg-btn-primary {
            background: rgba(246,216,58,0.15);
            border-color: rgba(246,216,58,0.40);
            color: var(--petal);
            box-shadow: 0 0 14px rgba(246,216,58,0.18), inset 0 1px 0 rgba(255,255,255,0.10);
        }
        .lg-btn-primary:hover {
            background: rgba(246,216,58,0.25);
            box-shadow: 0 0 24px rgba(246,216,58,0.28), inset 0 1px 0 rgba(255,255,255,0.15);
            transform: translateY(-1px);
        }
        .lg-btn-water {
            background: rgba(134,195,201,0.12);
            border-color: rgba(134,195,201,0.35);
            color: var(--water);
            box-shadow: 0 0 14px rgba(134,195,201,0.15), inset 0 1px 0 rgba(255,255,255,0.08);
        }
        .lg-btn-water:hover {
            background: rgba(134,195,201,0.20);
            box-shadow: 0 0 22px rgba(134,195,201,0.25);
            transform: translateY(-1px);
        }
        .lg-btn-plum {
            background: rgba(144,69,110,0.15);
            border-color: rgba(144,69,110,0.40);
            color: #c98ab8;
            box-shadow: 0 0 14px rgba(144,69,110,0.15), inset 0 1px 0 rgba(255,255,255,0.08);
        }
        .lg-btn-plum:hover {
            background: rgba(144,69,110,0.25);
            box-shadow: 0 0 22px rgba(144,69,110,0.25);
            transform: translateY(-1px);
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

        /* ── Liquid Glass Pill / Badge ───────────────────────────── */
        .lg-pill {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem; font-weight: 500;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid;
        }
        .lg-pill-petal  { background: rgba(246,216,58,0.12); border-color: rgba(246,216,58,0.30); color: var(--petal); }
        .lg-pill-leaf   { background: rgba(92,149,80,0.15);  border-color: rgba(92,149,80,0.35);  color: #8fcf80; }
        .lg-pill-water  { background: rgba(134,195,201,0.12);border-color: rgba(134,195,201,0.30);color: var(--water); }
        .lg-pill-plum   { background: rgba(144,69,110,0.15); border-color: rgba(144,69,110,0.35); color: #c98ab8; }
        .lg-pill-muted  { background: rgba(255,255,255,0.05);border-color: rgba(255,255,255,0.10);color: var(--text-muted); }

        /* ── Liquid Glass Input ──────────────────────────────────── */
        .lg-input {
            width: 100%;
            background: rgba(20,33,22,0.50);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(246,216,58,0.18);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            color: var(--text-primary);
            font-size: 0.9rem; font-family: 'Inter', sans-serif;
            transition: border-color 0.25s, box-shadow 0.25s;
            outline: none;
        }
        .lg-input:focus {
            border-color: rgba(246,216,58,0.45);
            box-shadow: 0 0 0 3px rgba(246,216,58,0.08), 0 0 16px rgba(246,216,58,0.10);
        }
        .lg-input::placeholder { color: rgba(138,171,140,0.55); }

        /* ── Art Nouveau Section Heading ─────────────────────────── */
        .nouveau-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem; font-weight: 700;
            font-style: italic;
            color: var(--petal);
            text-shadow: 0 0 20px rgba(246,216,58,0.25);
            letter-spacing: 0.01em;
            position: relative;
            display: inline-block;
        }
        .nouveau-heading::after {
            content: '';
            position: absolute; bottom: -6px; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--stamen), transparent);
        }

        .nouveau-subheading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.25rem; font-weight: 600;
            color: var(--water);
            letter-spacing: 0.02em;
        }

        /* ── Stat Card ───────────────────────────────────────────── */
        .stat-card {
            text-align: center;
            padding: 1.5rem 1rem;
        }
        .stat-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem; font-weight: 700;
            line-height: 1;
        }
        .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* ── Star Rating ─────────────────────────────────────────── */
        .star-rating {
            display: flex; gap: 0.25rem;
        }
        .star {
            font-size: 1.25rem; cursor: pointer;
            color: rgba(255,255,255,0.15);
            transition: color 0.15s, transform 0.15s;
            line-height: 1;
        }
        .star.filled, .star.hover { color: var(--petal); }
        .star:hover { transform: scale(1.2); }

        /* ── Wishlist Heart ──────────────────────────────────────── */
        .wishlist-heart {
            position: absolute; top: 10px; right: 10px; z-index: 5;
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            backdrop-filter: blur(12px);
            background: rgba(20,33,22,0.65);
            border: 1px solid rgba(144,69,110,0.30);
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .wishlist-heart:hover {
            background: rgba(144,69,110,0.20);
            border-color: rgba(144,69,110,0.55);
            transform: scale(1.1);
        }
        .wishlist-heart.active { background: rgba(144,69,110,0.30); border-color: rgba(144,69,110,0.65); }
        .wishlist-heart svg { transition: transform 0.2s; }
        .wishlist-heart:hover svg { transform: scale(1.15); }

        /* ── Book Cover Placeholder ──────────────────────────────── */
        .book-cover {
            width: 100%; aspect-ratio: 3/4;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .book-cover-placeholder {
            width: 100%; aspect-ratio: 3/4;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            background: linear-gradient(135deg, rgba(75,116,22,0.4), rgba(12,20,16,0.8));
            border-radius: 8px 8px 0 0;
            gap: 0.5rem;
        }

        /* ── Order History Row ───────────────────────────────────── */
        .order-row {
            display: flex; align-items: center; gap: 1rem;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            transition: background 0.2s;
        }
        .order-row:hover { background: rgba(246,216,58,0.04); }

        /* ── Alert / Flash Message ───────────────────────────────── */
        .crm-alert {
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-size: 0.875rem;
            backdrop-filter: blur(10px);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .crm-alert-success {
            background: rgba(92,149,80,0.15);
            border: 1px solid rgba(92,149,80,0.35);
            color: #8fcf80;
        }
        .crm-alert-error {
            background: rgba(144,69,110,0.15);
            border: 1px solid rgba(144,69,110,0.35);
            color: #c98ab8;
        }

        /* ── Pagination ──────────────────────────────────────────── */
        .crm-pagination a, .crm-pagination span {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
        }
        .crm-pagination a:hover {
            background: rgba(246,216,58,0.10);
            color: var(--petal);
        }
        .crm-pagination .active {
            background: rgba(246,216,58,0.18);
            border: 1px solid rgba(246,216,58,0.35);
            color: var(--petal);
        }

        /* ── Utility ─────────────────────────────────────────────── */
        .text-petal  { color: var(--petal); }
        .text-stamen { color: var(--stamen); }
        .text-leaf   { color: #8fcf80; }
        .text-water  { color: var(--water); }
        .text-plum   { color: #c98ab8; }
        .text-muted  { color: var(--text-muted); }
        .section-gap { margin-bottom: 2rem; }

        /* ── Animation Utilities ─────────────────────────────────── */
        @keyframes shimmer-gold {
            0%   { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .gold-shimmer {
            background: linear-gradient(90deg,
                var(--stamen) 0%, var(--petal) 35%, #fff8c0 50%, var(--petal) 65%, var(--stamen) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer-gold 4s linear infinite;
        }

        @keyframes fade-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fade-up 0.45s ease forwards; }

        .crm-main { flex: 1; padding: 2rem 1.5rem; }
        .crm-container { max-width: 1200px; margin: 0 auto; }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 768px) {
            .crm-nav { padding: 0 1rem; }
            .crm-main { padding: 1.5rem 1rem; }
            .crm-nav-links { display: none; }
        }
    </style>
</head>

<body>
    {{-- Animated Background --}}
    <div class="crm-bg"></div>

    {{-- Art Nouveau Corner Ornaments (SVG) --}}
    <svg class="nouveau-corner top-left" width="160" height="160" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 150 C10 80, 50 20, 150 10" stroke="#F6D83A" stroke-width="1" stroke-opacity="0.7" fill="none"/>
        <path d="M10 130 C10 70, 40 30, 120 10" stroke="#5C9550" stroke-width="0.8" stroke-opacity="0.5" fill="none"/>
        <path d="M10 100 C10 60, 30 30, 80 10" stroke="#86C3C9" stroke-width="0.6" stroke-opacity="0.4" fill="none"/>
        <!-- Petal ornament -->
        <ellipse cx="18" cy="18" rx="7" ry="12" fill="#F6D83A" fill-opacity="0.25" transform="rotate(-30 18 18)"/>
        <ellipse cx="30" cy="12" rx="5" ry="9" fill="#5C9550" fill-opacity="0.20" transform="rotate(-55 30 12)"/>
        <circle cx="14" cy="14" r="3" fill="#DDB911" fill-opacity="0.40"/>
        <!-- Tendril -->
        <path d="M8 8 C20 15, 15 35, 8 45 C15 40, 35 38, 45 25" stroke="#5C9550" stroke-width="0.8" stroke-opacity="0.5" fill="none"/>
        <path d="M15 5 C30 18, 20 40, 12 55" stroke="#F6D83A" stroke-width="0.7" stroke-opacity="0.4" fill="none"/>
        <!-- Leaf shapes -->
        <path d="M35 8 C38 18, 30 25, 22 20 C30 18, 35 12, 35 8Z" fill="#4B7416" fill-opacity="0.25"/>
        <path d="M50 6 C55 18, 45 26, 36 20 C45 18, 52 12, 50 6Z" fill="#5C9550" fill-opacity="0.20"/>
    </svg>

    <svg class="nouveau-corner top-right" width="160" height="160" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 150 C10 80, 50 20, 150 10" stroke="#F6D83A" stroke-width="1" stroke-opacity="0.7" fill="none"/>
        <path d="M10 130 C10 70, 40 30, 120 10" stroke="#5C9550" stroke-width="0.8" stroke-opacity="0.5" fill="none"/>
        <path d="M10 100 C10 60, 30 30, 80 10" stroke="#86C3C9" stroke-width="0.6" stroke-opacity="0.4" fill="none"/>
        <ellipse cx="18" cy="18" rx="7" ry="12" fill="#F6D83A" fill-opacity="0.25" transform="rotate(-30 18 18)"/>
        <circle cx="14" cy="14" r="3" fill="#DDB911" fill-opacity="0.40"/>
        <path d="M35 8 C38 18, 30 25, 22 20 C30 18, 35 12, 35 8Z" fill="#4B7416" fill-opacity="0.25"/>
    </svg>

    <svg class="nouveau-corner bot-left" width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8 8 C8 60, 40 100, 112 112" stroke="#90456e" stroke-width="0.8" stroke-opacity="0.4" fill="none"/>
        <path d="M8 25 C8 65, 35 95, 100 112" stroke="#86C3C9" stroke-width="0.6" stroke-opacity="0.35" fill="none"/>
        <ellipse cx="18" cy="18" rx="6" ry="10" fill="#90456e" fill-opacity="0.20" transform="rotate(20 18 18)"/>
    </svg>

    <svg class="nouveau-corner bot-right" width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8 8 C8 60, 40 100, 112 112" stroke="#90456e" stroke-width="0.8" stroke-opacity="0.4" fill="none"/>
        <path d="M8 25 C8 65, 35 95, 100 112" stroke="#86C3C9" stroke-width="0.6" stroke-opacity="0.35" fill="none"/>
        <ellipse cx="18" cy="18" rx="6" ry="10" fill="#90456e" fill-opacity="0.20" transform="rotate(20 18 18)"/>
    </svg>

    {{-- Main Wrapper --}}
    <div class="crm-wrapper">

        {{-- Liquid Glass Navbar --}}
        <nav class="crm-nav" role="navigation" aria-label="CRM Navigation">
            {{-- Logo --}}
            <a href="{{ route('crm.dashboard') }}" class="crm-nav-logo" id="crm-logo">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <path d="M4 18V4h10l4 4v10H4z" stroke="#F6D83A" stroke-width="1.3" fill="none"/>
                    <path d="M14 4v4h4" stroke="#F6D83A" stroke-width="1.3" fill="none"/>
                    <path d="M7 10h8M7 13h6M7 16h4" stroke="#DDB911" stroke-width="1" stroke-opacity="0.8"/>
                </svg>
                BookSoed
            </a>

            {{-- Nav Links --}}
            <div class="crm-nav-links" role="list">
                <a href="{{ route('crm.dashboard') }}"
                   class="crm-nav-link {{ request()->routeIs('crm.dashboard') ? 'active' : '' }}"
                   id="nav-dashboard">
                    Profil
                </a>
                <a href="{{ route('crm.wishlist') }}"
                   class="crm-nav-link {{ request()->routeIs('crm.wishlist') ? 'active' : '' }}"
                   id="nav-wishlist">
                    Wishlist
                </a>
            </div>

            {{-- User Info --}}
            <div class="crm-nav-user">
                @auth
                    <span class="text-muted" style="font-size:0.8rem">{{ Auth::user()->name }}</span>
                    <div class="crm-avatar" title="{{ Auth::user()->email }}" id="crm-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endauth
            </div>
        </nav>

        {{-- Flash Alert --}}
        <div class="crm-container" style="padding: 0 1.5rem; margin: 0 auto; max-width: 1200px">
            @if(session('crm_status'))
                <div class="crm-alert crm-alert-success mt-4 fade-up" id="crm-flash-alert" role="alert">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="8" r="7" stroke="#8fcf80" stroke-width="1.2"/>
                        <path d="M5 8l2 2 4-4" stroke="#8fcf80" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    {{ session('crm_status') }}
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="crm-main" id="crm-content">
            <div class="crm-container fade-up">
                {{ $slot }}
            </div>
        </main>

        {{-- Footer ornament --}}
        <footer style="text-align:center; padding: 1.5rem; opacity: 0.3; font-size: 0.7rem; color: var(--text-muted); font-family: 'Cormorant Garamond', serif; font-style: italic;">
            BookSoed &mdash; Civitas Akademika Unsoed &bull; CRM Module
        </footer>
    </div>

    <script>
        // Auto-dismiss flash alert
        const alert = document.getElementById('crm-flash-alert');
        if (alert) setTimeout(() => { alert.style.opacity = '0'; alert.style.transition = 'opacity 0.4s'; setTimeout(() => alert.remove(), 400); }, 4000);
    </script>

    @stack('scripts')
</body>
</html>
