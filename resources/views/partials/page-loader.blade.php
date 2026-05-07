{{-- Modern Page Loader --}}
<style>
    /* ===== Overlay ===== */
    #page-loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(12px) saturate(180%);
        -webkit-backdrop-filter: blur(12px) saturate(180%);
        z-index: 9998;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                    visibility 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #page-loader-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* ===== Progress Bar (Top) ===== */
    #page-loader-bar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        z-index: 9999;
        pointer-events: none;
        overflow: hidden;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    #page-loader-bar.active {
        opacity: 1;
    }
    #page-loader-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 40%;
        background: linear-gradient(90deg, 
            #0d9488 0%, 
            #14b8a6 25%, 
            #2dd4bf 50%, 
            #14b8a6 75%, 
            #0d9488 100%);
        background-size: 200% 100%;
        border-radius: 0 2px 2px 0;
        animation: loaderBarSlide 1.4s cubic-bezier(0.4, 0, 0.2, 1) infinite,
                   loaderBarShimmer 1s linear infinite;
        box-shadow: 0 0 12px rgba(20, 184, 166, 0.5);
    }
    @keyframes loaderBarSlide {
        0%   { transform: translateX(-110%); width: 30%; }
        50%  { transform: translateX(20%); width: 50%; }
        100% { transform: translateX(250%); width: 30%; }
    }
    @keyframes loaderBarShimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* ===== Orb Container ===== */
    .loader-orb-container {
        position: relative;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Central glowing orb */
    .loader-orb-core {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: linear-gradient(135deg, #14b8a6, #0d9488);
        box-shadow: 
            0 0 20px rgba(20, 184, 166, 0.6),
            0 0 40px rgba(20, 184, 166, 0.3),
            0 0 60px rgba(20, 184, 166, 0.15);
        animation: orbPulse 2s ease-in-out infinite;
    }

    /* Orbiting rings */
    .loader-orb-ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid transparent;
    }
    .loader-orb-ring:nth-child(2) {
        border-top-color: rgba(20, 184, 166, 0.8);
        border-right-color: rgba(20, 184, 166, 0.2);
        animation: orbSpin 1.8s linear infinite;
    }
    .loader-orb-ring:nth-child(3) {
        inset: 8px;
        border-bottom-color: rgba(45, 212, 191, 0.7);
        border-left-color: rgba(45, 212, 191, 0.15);
        animation: orbSpinReverse 2.4s linear infinite;
    }
    .loader-orb-ring:nth-child(4) {
        inset: 16px;
        border-top-color: rgba(13, 148, 136, 0.6);
        border-right-color: rgba(13, 148, 136, 0.1);
        animation: orbSpin 3s linear infinite;
    }

    /* Orbiting dot */
    .loader-orb-dot {
        position: absolute;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #2dd4bf;
        box-shadow: 0 0 8px rgba(45, 212, 191, 0.8);
        animation: orbDotOrbit 2s ease-in-out infinite;
    }

    @keyframes orbPulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 
                0 0 20px rgba(20, 184, 166, 0.6),
                0 0 40px rgba(20, 184, 166, 0.3),
                0 0 60px rgba(20, 184, 166, 0.15);
        }
        50% { 
            transform: scale(1.15);
            box-shadow: 
                0 0 30px rgba(20, 184, 166, 0.8),
                0 0 60px rgba(20, 184, 166, 0.4),
                0 0 90px rgba(20, 184, 166, 0.2);
        }
    }
    @keyframes orbSpin {
        to { transform: rotate(360deg); }
    }
    @keyframes orbSpinReverse {
        to { transform: rotate(-360deg); }
    }
    @keyframes orbDotOrbit {
        0%   { transform: rotate(0deg) translateX(36px) rotate(0deg); }
        100% { transform: rotate(360deg) translateX(36px) rotate(-360deg); }
    }

    /* ===== Label ===== */
    .loader-label {
        margin-top: 24px;
        font-size: 13px;
        font-weight: 600;
        color: #e2e8f0;
        letter-spacing: 0.08em;
        text-align: center;
        text-transform: uppercase;
    }
    .loader-label-dots::after {
        content: '';
        animation: loaderDots 1.5s steps(4, end) infinite;
    }
    @keyframes loaderDots {
        0%   { content: ''; }
        25%  { content: '.'; }
        50%  { content: '..'; }
        75%  { content: '...'; }
        100% { content: ''; }
    }

    .loader-subtitle {
        margin-top: 6px;
        font-size: 11px;
        font-weight: 400;
        color: rgba(148, 163, 184, 0.7);
        letter-spacing: 0.02em;
        text-align: center;
    }

    /* ===== Card behind loader ===== */
    .loader-card {
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 40px 48px;
        display: flex;
        flex-direction: column;
        align-items: center;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.5),
            0 0 0 1px rgba(255, 255, 255, 0.05) inset;
        transform: scale(0.92) translateY(8px);
        -webkit-transform: scale(0.92) translateY(8px);
        transform-origin: center center;
        -webkit-transform-origin: center center;
        opacity: 0;
        transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1),
                    opacity 0.4s ease;
    }
    #page-loader-overlay.active .loader-card {
        transform: scale(1) translateY(0);
        -webkit-transform: scale(1) translateY(0);
        opacity: 1;
    }

    /* ===== Reduced Motion ===== */
    @media (prefers-reduced-motion: reduce) {
        #page-loader-bar::before,
        .loader-orb-core,
        .loader-orb-ring,
        .loader-orb-dot,
        .loader-label-dots::after {
            animation: none !important;
        }
        .loader-orb-core {
            opacity: 0.8;
        }
        .loader-card {
            transform: none;
            opacity: 1;
        }
    }

    /* ===== Mobile optimizations ===== */
    @media (max-width: 640px) {
        .loader-card {
            padding: 32px 36px;
            margin: auto 20px;
            max-width: calc(100% - 40px);
        }
        .loader-orb-container {
            width: 64px;
            height: 64px;
        }
        .loader-orb-core {
            width: 12px;
            height: 12px;
        }
        .loader-orb-ring:nth-child(3) { top: 6px; left: 6px; right: 6px; bottom: 6px; }
        .loader-orb-ring:nth-child(4) { top: 12px; left: 12px; right: 12px; bottom: 12px; }
    }
</style>

<div id="page-loader-bar"></div>
<div id="page-loader-overlay">
    <div class="loader-card">
        <div class="loader-orb-container">
            <div class="loader-orb-ring"></div>
            <div class="loader-orb-ring"></div>
            <div class="loader-orb-ring"></div>
            <div class="loader-orb-dot"></div>
            <div class="loader-orb-core"></div>
        </div>
        <div class="loader-label">Loading<span class="loader-label-dots"></span></div>
        <div class="loader-subtitle">Tugawe Elementary School</div>
    </div>
</div>

<script>
    (function() {
        const bar = document.getElementById('page-loader-bar');
        const overlay = document.getElementById('page-loader-overlay');
        let loaderTimeout = null;

        function show() {
            if (loaderTimeout) clearTimeout(loaderTimeout);
            if (bar) bar.classList.add('active');
            if (overlay) overlay.classList.add('active');
        }

        function hide() {
            if (bar) bar.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
        }

        // Always hide on script run
        hide();

        // Hide when page becomes visible again (critical for bfcache/back button)
        window.addEventListener('pageshow', function(event) {
            loaderTimeout = setTimeout(hide, 50);
        });

        // Backup: hide when tab becomes visible
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                loaderTimeout = setTimeout(hide, 50);
            }
        });

        // Show on link clicks (same-domain only)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href) return;
            if (href.startsWith('#') || href.startsWith('javascript:') ||
                href.startsWith('mailto:') || href.startsWith('tel:')) return;
            if (link.target === '_blank' || link.hasAttribute('download')) return;
            if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
            show();
        });

        // Show on form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.method === 'get' || !form.method) return;
            show();
        });
    })();
</script>
