@php
    // Variables are now passed from AuthenticatedSessionController for better performance
@endphp

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tugawe Elementary School | Official Website</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Modern Color Palette - Teal & Coral Theme */
        :root {
            --primary: #0d9488;      /* Teal 600 */
            --primary-dark: #0f766e; /* Teal 700 */
            --primary-light: #14b8a6; /* Teal 500 */
            --accent: #f97316;       /* Orange 500 */
            --accent-light: #fb923c; /* Orange 400 */
            --bg-warm: #fdf8f6;      /* Warm off-white */
            --text-dark: #1e293b;    /* Slate 800 */
            --text-muted: #64748b;   /* Slate 500 */
        }

        .grain {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 50;
            opacity: 0.02;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
        }

        /* Animated Background */
        .hero-bg {
            background: linear-gradient(135deg, #f0fdfa 0%, #fdf8f6 50%, #fff7ed 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(20, 184, 166, 0.15) 0%, transparent 70%);
            animation: float 20s infinite ease-in-out;
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
            animation: float 25s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .glass-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Text Balance */
        .text-balance { text-wrap: balance; }
        
        /* Card Hover Effects */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(13, 148, 136, 0.15);
        }

        /* Primary Button */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        /* Dark Mode Overrides for Login Page */
        .dark .hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
        .dark .hero-bg::before {
            background: radial-gradient(circle, rgba(20, 184, 166, 0.08) 0%, transparent 70%);
        }
        .dark .hero-bg::after {
            background: radial-gradient(circle, rgba(249, 115, 22, 0.05) 0%, transparent 70%);
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.7);
            border-color: rgba(255, 255, 255, 0.08);
        }
        .dark .glass-dark {
            background: rgba(15, 23, 42, 0.85);
            border-color: rgba(255, 255, 255, 0.1);
        }
        .dark .card-hover:hover {
            box-shadow: 0 20px 40px -15px rgba(13, 148, 136, 0.25);
        }
        .dark .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px rgba(13, 148, 136, 0.4);
        }

        /* ===== Button Loading Shimmer ===== */
        .btn-shimmer {
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255,255,255,0) 20%,
                rgba(255,255,255,0.25) 50%, 
                rgba(255,255,255,0) 80%,
                transparent 100%);
            background-size: 200% 100%;
            animation: btnShimmerSweep 1.5s ease-in-out infinite;
        }
        @keyframes btnShimmerSweep {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        button.is-loading {
            transform: none !important;
            box-shadow: 0 4px 12px -4px rgba(13, 148, 136, 0.3) !important;
            cursor: wait !important;
        }
        button.is-loading .btn-shimmer {
            opacity: 1 !important;
        }
        button.is-loading #loginBtnText,
        button.is-loading #regBtnText {
            opacity: 0.85;
        }

        /* Spinner animation */
        @keyframes btnSpin {
            to { transform: rotate(360deg); }
        }
        #loginSpinner, #regSpinner {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        button.is-loading #loginSpinner,
        button.is-loading #regSpinner {
            animation: btnSpin 0.7s linear infinite;
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .btn-shimmer { animation: none; }
            button.is-loading #loginSpinner,
            button.is-loading #regSpinner { animation: none; }
        }

        /* Accent Button */
        .btn-accent {
            background: linear-gradient(135deg, var(--accent) 0%, #ea580c 100%);
            transition: all 0.3s ease;
        }
        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px rgba(249, 115, 22, 0.4);
        }

        /* Navigation Link Animation */
        .nav-link {
            position: relative;
            color: var(--text-muted);
            transition: color 0.3s;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        .nav-link:hover {
            color: var(--primary-dark);
        }
        .nav-link:hover::after {
            width: 100%;
        }

        /* Side Panel Animation */
      .side-panel {
    position: fixed;
    top: 0;
    right: -100%;
    width: 100%;
    max-width: 100%;
    height: 100vh;
    background: white;
    z-index: 100;
    transition: right 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: -10px 0 40px rgba(0,0,0,0.1);
    overflow-y: auto;
}

@media (min-width: 640px) {
    .side-panel {
        max-width: 480px;
    }
}

        .side-panel.active {
            right: 0;
        }

        .side-panel-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .side-panel-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Form Slide Animation */
        .form-container {
            position: relative;
            overflow: hidden;
            min-height: 400px;
        }

        .form-slide {
            position: absolute;
            width: 100%;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateX(50px);
            pointer-events: none;
        }

        .form-slide.active {
            opacity: 1;
            transform: translateX(0);
            pointer-events: all;
            position: relative;
        }

        .form-slide.exit-left {
            opacity: 0;
            transform: translateX(-50px);
        }

        /* Decorative Elements */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.4;
            animation: blob-float 10s infinite ease-in-out;
        }

        @keyframes blob-float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Stats Counter Animation */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid rgba(13, 148, 136, 0.1);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(13, 148, 136, 0.3);
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Input Focus Effects */
        .input-field {
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
        }
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        /* Custom Checkbox */
        .custom-checkbox {
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            border-radius: 0.375rem;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        .custom-checkbox:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* Extra Small Screen Optimizations */
@media (max-width: 375px) {
    .hero-bg h1 {
        font-size: 2.25rem !important;
        line-height: 1.2 !important;
    }
    
    .side-panel {
        max-width: 100% !important;
    }
    
    .max-w-7xl {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
}

/* Prevent horizontal overflow */
html, body {
    max-width: 100%;
    overflow-x: hidden;
}

/* Ensure images don't overflow */
img {
    max-width: 100%;
    height: auto;
}

/* Touch-friendly tap targets on mobile */
@media (max-width: 768px) {
    button, 
    .nav-link,
    input,
    select {
        min-height: 44px;
    }
    
    h2 {
        font-size: 1.875rem !important;
    }
    
    h3 {
        font-size: 1.5rem !important;
    }
}
    </style>
</head>

<body class="antialiased text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-950">

    <div class="grain"></div>

    <!-- Navigation -->
    <nav class="fixed w-full z-40 glass border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-20">
                <a href="#" class="flex items-center gap-3 group">
                    <div class="relative">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-12 rounded-xl object-cover shadow-lg group-hover:scale-105 transition-transform">
                        <div class="absolute inset-0 bg-teal-500/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-base font-bold text-slate-900 leading-tight">Tugawe Elementary School</p>
                        <p class="text-xs text-teal-600 font-medium">DepEd Negros Oriental</p>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#about" class="nav-link text-sm font-medium">About</a>
                    <a href="#announcements" class="nav-link text-sm font-medium">Announcements</a>
                    <a href="#faculty" class="nav-link text-sm font-medium">Faculty</a>
                    <button onclick="openAuthPanel('login')" class="btn-primary text-white text-sm font-semibold px-6 py-2.5 rounded-full shadow-lg shadow-teal-500/30">
                        Sign In
                    </button>
                </div>

                <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-slate-600 hover:text-teal-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden glass border-t border-slate-200">
            <div class="px-6 py-4 space-y-3">
                <a href="#about" class="block text-sm font-medium text-slate-600 py-2 hover:text-teal-600">About</a>
                <a href="#announcements" class="block text-sm font-medium text-slate-600 py-2 hover:text-teal-600">Announcements</a>
                <a href="#faculty" class="block text-sm font-medium text-slate-600 py-2 hover:text-teal-600">Faculty</a>
                <button onclick="openAuthPanel('login')" class="w-full btn-primary text-white text-sm font-semibold px-5 py-3 rounded-full mt-2 shadow-lg shadow-teal-500/30">
                    Sign In to Portal
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center pt-20 relative">
        <!-- Decorative Blobs -->
        <div class="blob bg-teal-300 w-96 h-96 top-20 -left-20"></div>
        <div class="blob bg-orange-300 w-80 h-80 bottom-20 right-10 animation-delay-2000"></div>
        
        <div class="max-w-7xl mx-auto px-6 py-20 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teal-50 border border-teal-100 mb-6">
                        <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                        <span class="text-sm font-semibold text-teal-700">Department of Education • Negros Island Region</span>
                    </div>
                    
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 leading-[1.1] mb-6 text-balance">
                        Building <span class="gradient-text">foundations</span> for lifelong learning
                    </h1>
                    
                    <p class="text-lg md:text-xl text-slate-600 mb-8 leading-relaxed text-balance max-w-xl text-justify">
                        Tugawe Elementary School provides quality basic education to the children of 
                        Dauin, Negros Oriental. Committed to academic excellence and holistic development.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <button onclick="openModal('enrollTermsModal')" class="btn-primary text-white px-8 py-4 rounded-full font-semibold inline-flex items-center justify-center gap-2 shadow-xl shadow-teal-500/30 text-base bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700">
                            <i class="fas fa-user-plus"></i>
                            Enroll Now
                        </button>
                        <button onclick="openModal('locationModal')" class="px-8 py-4 rounded-full font-semibold inline-flex items-center justify-center gap-2 shadow-lg border-2 border-slate-200 text-slate-700 hover:border-teal-500 hover:text-teal-700 hover:bg-teal-50 transition-all duration-300 text-base">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Location
                        </button>
                    </div>

                    <!-- Quick Stats -->
                   <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-12 pt-8 border-t border-slate-200/60">
                        <div>
                            <p class="text-3xl font-bold text-teal-600">{{ $studentCount }}+</p>
                            <p class="text-sm text-slate-500 font-medium">Students</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-orange-500">{{ $teachers->count() }}+</p>
                            <p class="text-sm text-slate-500 font-medium">Teachers</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-teal-600">{{ $sectionCount }}</p>
                            <p class="text-sm text-slate-500 font-medium">Sections</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Image/Illustration Area - Alpine.js Slider -->
<div class="relative mt-8 lg:mt-0" x-data="imageSlider()" x-init="startAutoPlay()">
    <div class="relative z-10 bg-white p-4 rounded-3xl shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500">
        <div class="aspect-[4/3] bg-gradient-to-br from-teal-50 to-orange-50 rounded-2xl overflow-hidden relative">
            
            <!-- Slider Container -->
            <div class="relative w-full h-full group">
                
                <!-- Slides -->
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="currentSlide === index"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 transform scale-105"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute inset-0">
                        <img :src="slide" class="w-full h-full object-cover" alt="School Photo" loading="lazy">
                    </div>
                </template>
                
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-teal-900/20 to-transparent pointer-events-none"></div>
                
                <!-- Navigation Arrows -->
                <button @click="prevSlide()" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                <button @click="nextSlide()" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                
                <!-- Dots Indicator -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="goToSlide(index)"
                                :class="currentSlide === index ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/80'"
                                class="h-2 rounded-full transition-all duration-300 w-2"></button>
                    </template>
                </div>
                
                <!-- Slide Counter -->
                <div class="absolute top-4 right-4 bg-black/30 backdrop-blur-sm text-white text-xs px-3 py-1 rounded-full">
                    <span x-text="currentSlide + 1"></span> / <span x-text="slides.length"></span>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- Floating Card -->
    <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl border border-slate-100 z-20 max-w-xs animate-bounce" style="animation-duration: 3s;">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900">DepEd Certified</p>
                <p class="text-xs text-slate-500">Excellence in Education</p>
            </div>
        </div>
    </div>
</div>

<!-- Add this script -->
<script>
function imageSlider() {
    return {
        slides: [
            '{{ asset("images/tes2.jpg") }}',
            '{{ asset("images/tes1.jpg") }}',
            '{{ asset("images/tes3.jpg") }}',
            '{{ asset("images/tes4.jpg") }}',
            '{{ asset("images/tes5.jpg") }}',
            '{{ asset("images/tes6.jpg") }}',
            '{{ asset("images/tes7.jpg") }}',
            '{{ asset("images/tes8.jpg") }}',
            '{{ asset("images/tes11.jpg") }}',
            '{{ asset("images/tes12.jpg") }}',
            '{{ asset("images/tes13.jpg") }}',
            '{{ asset("images/tes14.jpg") }}',

        ],
        currentSlide: 0,
        autoPlayInterval: null,
        
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            this.resetAutoPlay();
        },
        
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            this.resetAutoPlay();
        },
        
        goToSlide(index) {
            this.currentSlide = index;
            this.resetAutoPlay();
        },
        
        startAutoPlay() {
            this.autoPlayInterval = setInterval(() => {
                this.nextSlide();
            }, 5000); // Change every 5 seconds
        },
        
        resetAutoPlay() {
            clearInterval(this.autoPlayInterval);
            this.startAutoPlay();
        }
    }
}
</script>
            </div>
        </div>
    </section>

   <!-- About Section -->
<section id="about" class="py-24 bg-white relative overflow-hidden">
    
    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#0d9488 1px, transparent 1px); background-size: 32px 32px;"></div>
    
    <!-- Gradient Blobs -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-teal-100/40 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-orange-100/40 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <!-- Logo Section - Large & Clean -->
            <div class="order-2 lg:order-1 relative flex flex-col items-center">
                
                <!-- Main Logo Container -->
                <div class="relative group">
                    
                    <!-- Glow Effect Behind Logo -->
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-400/20 to-orange-400/20 rounded-full blur-3xl scale-150 group-hover:scale-175 transition-transform duration-700"></div>
                    
                    <!-- Logo Circle Frame -->
                  <div class="relative w-64 h-64 sm:w-72 sm:h-72 md:w-80 md:h-80 lg:w-96 lg:h-96 rounded-full bg-white shadow-2xl flex items-center justify-center border-8 border-white group-hover:shadow-3xl transition-all duration-500">
                        
                        <!-- Inner Ring -->
                        <div class="absolute inset-4 rounded-full border-2 border-dashed border-teal-200/60 animate-spin-slow" style="animation-duration: 20s;"></div>
                        
                        <!-- Logo Image - No Background, Bigger -->
                      <div class="relative w-48 h-48 sm:w-56 sm:h-56 md:w-72 md:h-72 lg:w-80 lg:h-80 p-4">
                            <img src="{{ asset('images/logo.png') }}" 
                                 alt="Tugawe Elementary School Logo" 
                                 class="w-full h-full object-contain drop-shadow-2xl
                                        group-hover:scale-105 group-hover:-rotate-2 transition-all duration-700 ease-out">
                        </div>
                        
                        <!-- Shine Sweep -->
                        <div class="absolute inset-0 rounded-full overflow-hidden pointer-events-none">
                            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/30 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                        </div>
                    </div>
                    
                    <!-- Floating Elements Around Logo -->
                    <div class="absolute -top-4 -right-4 w-16 h-16 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl rotate-12 shadow-lg flex items-center justify-center text-white font-bold text-xl animate-bounce" style="animation-duration: 3s;">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    
                    <div class="absolute -bottom-2 -left-6 bg-white px-4 py-3 rounded-xl shadow-xl border border-slate-100">
                        <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-600"></p>
                    </div>
                </div>
                
                <!-- School Name Below Logo -->
                <div class="mt-8 text-center">
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                        TUGAWE ELEMENTARY SCHOOL
                    </h3>
                    <p class="text-slate-500 font-medium mt-1">Brgy. Tugawe, Dauin, Negros Oriental</p>
                    
                    <!-- Decorative Line -->
                    <div class="flex items-center justify-center gap-2 mt-4">
                        <div class="h-1 w-12 bg-teal-500 rounded-full"></div>
                        <div class="h-1 w-3 bg-orange-500 rounded-full"></div>
                        <div class="h-1 w-3 bg-yellow-500 rounded-full"></div>
                        <div class="h-1 w-12 bg-teal-500 rounded-full"></div>
                    </div>
                </div>
                
                <!-- Mission Card - Bottom Right -->
                <div class="absolute -bottom-4 right-0 lg:right-[-2rem] bg-white/95 backdrop-blur p-6 rounded-2xl shadow-2xl border border-slate-100 max-w-xs
                            hover:-translate-y-2 hover:shadow-3xl transition-all duration-500 group/card">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-500/30 shrink-0
                                    group-hover/card:scale-110 transition-transform duration-500">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-slate-900 mb-1">Our Mission</p>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Provide quality basic education accessible to all learners.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Text Content Section -->
            <div class="order-1 lg:order-2 space-y-8">
                
                <!-- Section Label -->
                <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-teal-50 border border-teal-100">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                    </span>
                    <span class="text-sm font-bold text-teal-700 uppercase tracking-wider">About Our School</span>
                </div>
                
                <!-- Heading -->
                <div class="space-y-4">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 leading-[1.1]">
                        Nurturing 
                        <span class="relative inline-block">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-teal-400">young minds</span>
                            <svg class="absolute -bottom-2 left-0 w-full h-4 text-teal-200" viewBox="0 0 300 12" preserveAspectRatio="none">
                                <path d="M0 8 Q 75 0 150 8 T 300 8" stroke="currentColor" stroke-width="6" fill="none" opacity="0.6"/>
                            </svg>
                        </span>
                        for a brighter tomorrow
                    </h2>
                    <p class="text-xl text-slate-600 leading-relaxed max-w-lg text-justify">
                        A premier public elementary institution committed to academic excellence and holistic development.
                    </p>
                </div>
                
                <!-- Description -->
                <div class="prose prose-slate text-slate-600 leading-relaxed space-y-4 text-lg">
                    <p class="text-justify">
                        <span class="text-5xl font-black text-teal-600 float-left mr-1 mt-[-4px] leading-[0.85]">T</span>ugawe Elementary School stands as a beacon of quality education in Barangay Tugawe, serving the Dauin district and neighboring communities across Negros Oriental.
                    </p>
                    <p class="text-justify">
                        Under the Department of Education, we are dedicated to molding <strong class="text-slate-800">academically competent</strong>, <strong class="text-slate-800">socially responsible</strong>, and <strong class="text-slate-800">morally upright</strong> individuals ready to face future challenges.
                    </p>
                </div>
                
                <!-- Stats Grid - Modern Cards -->
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="group p-5 rounded-2xl bg-slate-50 hover:bg-white border border-transparent hover:border-teal-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl bg-teal-100 flex items-center justify-center group-hover:bg-teal-500 transition-colors duration-300">
                                <svg class="w-6 h-6 text-teal-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-black text-slate-900 group-hover:text-teal-600 transition-colors">120231</p>
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-wide mt-1">School ID</p>
                    </div>
                    
                    <div class="group p-5 rounded-2xl bg-slate-50 hover:bg-white border border-transparent hover:border-orange-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-500 transition-colors duration-300">
                                <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-black text-slate-900 group-hover:text-orange-600 transition-colors">Dauin</p>
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-wide mt-1">District</p>
                    </div>
                    
                    <div class="group p-5 rounded-2xl bg-slate-50 hover:bg-white border border-transparent hover:border-blue-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-500 transition-colors duration-300">
                                <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">Negros Oriental</p>
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-wide mt-1">Division</p>
                    </div>
                    
                    <div class="group p-5 rounded-2xl bg-slate-50 hover:bg-white border border-transparent hover:border-purple-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-500 transition-colors duration-300">
                                <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-slate-900 group-hover:text-purple-600 transition-colors">NIR</p>
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-wide mt-1">Negros Island Region</p>
                    </div>
                </div>
                
               
            </div>
        </div>
    </div>
    
    <!-- Custom Styles -->
    <style>
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 20s linear infinite;
        }
    </style>
</section>

    <!-- Announcements Section -->
    <section id="announcements" class="py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-100 mb-4">
                        <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">School Updates</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold text-slate-900">Announcements</h2>
                </div>
                @if($announcements->count() > 3)
                <button onclick="openModal('allAnnouncementsModal')" class="group flex items-center gap-2 text-sm font-semibold text-teal-600 hover:text-teal-700 transition-colors bg-white px-4 py-2 rounded-full shadow-md hover:shadow-lg">
                    View all updates
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>
                @endif
            </div>

            @if($announcements->count())
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($announcements->take(3) as $announcement)
                <article class="bg-white rounded-2xl p-6 card-hover border border-slate-100 relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-teal-500 to-teal-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                        <time class="text-sm font-semibold text-slate-400">
                            {{ $announcement->created_at->format('F d, Y') }}
                        </time>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-teal-600 transition-colors">
                        {{ $announcement->title }}
                    </h3>
                    
                    <p class="text-slate-600 line-clamp-3 mb-6 leading-relaxed">
                        {{ $announcement->message }}
                    </p>
                    
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center text-sm font-bold text-teal-700">
                            {{ $announcement->author ? substr($announcement->author->full_name ?? $announcement->author->name ?? 'A', 0, 1) : 'A' }}
                        </div>
                        <div>
                            @php
                                $authorName = $announcement->author?->full_name ?? $announcement->author?->name ?? 'Administrator';
                                $authorRole = 'Administrator';
                                if ($announcement->author && $announcement->author->role) {
                                    $roleName = strtolower($announcement->author->role->name ?? '');
                                    if ($roleName === 'teacher' && $announcement->author->teacher) {
                                        $teacherSections = $announcement->author->teacher->sections;
                                        if ($teacherSections->isNotEmpty()) {
                                            $firstSection = $teacherSections->first();
                                            $gradeName = $firstSection->gradeLevel?->name ?? '';
                                            $authorRole = $gradeName ? $gradeName . ' Adviser' : 'Teacher';
                                        } else {
                                            $authorRole = 'Teacher';
                                        }
                                    } elseif ($roleName === 'admin') {
                                        $authorRole = 'Administrator';
                                    }
                                }
                            @endphp
                            <span class="text-sm font-semibold text-slate-900 block">{{ $authorName }}</span>
                            <span class="text-xs text-slate-500">{{ $authorRole }}</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="text-center py-16 bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-slate-500 font-medium">No announcements posted at this time.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Faculty Section -->
    <section id="faculty" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-teal-50/30 to-transparent"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 border border-orange-100 mb-4">
                    <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Our People</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">School Faculty & Staff</h2>
                <p class="text-lg text-slate-600 leading-relaxed">
                    Meet the dedicated educators and administrators committed to providing 
                    quality education to our learners.
                </p>
            </div>

            @if($teachers->count())
            <!-- Principal -->
            @if($principal)
            <div class="flex justify-center mb-16">
                <div class="text-center group">
                    <div class="relative inline-block mb-6">
                        <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-orange-400 rounded-3xl rotate-6 opacity-20 group-hover:rotate-12 transition-transform"></div>
                        <div class="relative w-40 h-40 mx-auto rounded-3xl overflow-hidden bg-slate-100 shadow-2xl border-4 border-white">
                            <img src="{{ $principal->user?->photo ? profile_photo_url($principal->user->photo) : asset('images/photo-placeholder.png') }}" 
                                 alt="{{ $principal->first_name }} {{ $principal->last_name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 loading="lazy">
                        </div>
                        <div class="absolute -bottom-3 -right-3 w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $principal->first_name }} {{ $principal->last_name }}</h3>
                    <p class="text-teal-600 font-semibold">School Principal</p>
                </div>
            </div>
            @endif

            <!-- Teaching Staff Preview -->
            @if($teachingStaff->count())
         <div class="grid grid-cols-2 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
                @foreach($teachingStaff->take(6) as $teacher)
                <div class="text-center group">
                    <div class="relative mb-4 mx-auto w-24 h-24">
                        <div class="absolute inset-0 bg-gradient-to-br from-teal-200 to-orange-200 rounded-2xl rotate-3 opacity-0 group-hover:opacity-100 group-hover:rotate-6 transition-all duration-300"></div>
                        <div class="relative w-24 h-24 mx-auto rounded-2xl overflow-hidden bg-slate-100 shadow-lg border-2 border-white">
                            <img src="{{ $teacher->user?->photo ? profile_photo_url($teacher->user->photo) : asset('images/photo-placeholder.png') }}"
                                 alt="{{ $teacher->first_name }} {{ $teacher->last_name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                 loading="lazy">
                        </div>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 truncate px-2 mb-1">
                        {{ $teacher->first_name }} {{ $teacher->last_name }}
                    </h4>
                    <p class="text-xs text-teal-600 font-semibold bg-teal-50 px-2 py-0.5 rounded-full inline-block mb-1">
                        {{ $teacher->position ?? 'Teacher' }}
                    </p>
                    @php
                        $teacherGradeLevels = $teacher->sections->pluck('gradeLevel.name')->filter()->unique()->values();
                    @endphp
                    @if($teacherGradeLevels->count())
                        <p class="text-[10px] text-slate-500">
                            {{ $teacherGradeLevels->implode(', ') }}
                        </p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if($teachers->count() > 7)
            <div class="text-center mt-12">
                <button onclick="openModal('facultyModal')" class="group inline-flex items-center gap-2 px-8 py-4 rounded-full border-2 border-slate-200 text-sm font-semibold text-slate-700 hover:border-teal-500 hover:text-teal-600 hover:bg-teal-50 transition-all shadow-md hover:shadow-lg">
                    View complete faculty list
                    <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
            @endif
            @else
            <div class="text-center py-16 bg-slate-50 rounded-2xl">
                <p class="text-slate-500 font-medium">Faculty information currently unavailable.</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-24 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-teal-900/20 to-slate-900"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold mb-6">Get in touch</h2>
                    <p class="text-slate-400 text-lg mb-10 leading-relaxed">
                        For inquiries regarding enrollment, pupil records, or other school matters, 
                        please contact us or visit the school administration office.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4 group">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-teal-500/20 transition-colors">
                                <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-lg mb-1">Address</p>
                                <p class="text-slate-400">Tugawe, Dauin, Negros Oriental, Philippines</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4 group">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-orange-500/20 transition-colors">
                                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-lg mb-1">Email</p>
                                <p class="text-slate-400">tugaweelementaryschool@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="glass-dark rounded-3xl p-8 border border-white/10">
                    <h3 class="text-2xl font-bold mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                        School Hours
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-4 border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-teal-500/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-slate-300">Monday - Friday</span>
                            </div>
                            <span class="font-semibold text-lg">7:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-b border-white/10 opacity-60">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-700 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                    </svg>
                                </div>
                                <span class="text-slate-400">Saturday & Sunday</span>
                            </div>
                            <span class="font-medium">Closed</span>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-white/10">
                        <div class="flex items-start gap-3 text-sm text-slate-400">
                            <svg class="w-5 h-5 text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>For urgent matters, please contact the school directly during operating hours.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 py-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" class="h-10 w-10 rounded-lg object-cover opacity-80 ring-2 ring-teal-500/30">
                    <div>
                        <span class="text-white font-bold block">Tugawe Elementary School</span>
                        <span class="text-xs">Excellence in Education</span>
                    </div>
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <a href="javascript:void(0)" onclick="openModal('privacyPolicyModal')" class="hover:text-teal-400 transition-colors">Privacy Policy</a>
                    <a href="javascript:void(0)" onclick="openModal('termsOfUseModal')" class="hover:text-teal-400 transition-colors">Terms of Use</a>
                    <a href="javascript:void(0)" onclick="openModal('contactModal')" class="hover:text-teal-400 transition-colors">Contact</a>
                </div>
                <p class="text-xs text-slate-600">
                    © {{ date('Y') }} Department of Education. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Side Panel Auth Container -->
    <div id="sidePanelOverlay" class="side-panel-overlay" onclick="closeAuthPanel()"></div>
    
    <div id="authSidePanel" class="side-panel">
        <div class="min-h-full flex flex-col bg-gradient-to-b from-slate-50/50 to-white">
            <!-- Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img src="{{ asset('images/logo.png') }}" class="h-10 w-10 rounded-xl object-cover shadow-md ring-2 ring-teal-100">
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-teal-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-900 text-lg">Tugawe ES Portal</h2>
                        <p class="text-xs text-slate-500">Pupil Management System</p>
                    </div>
                </div>
                <button onclick="closeAuthPanel()" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-700 transition-all duration-200 hover:rotate-90">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Auth Mode Indicator -->
            <div class="px-6 pt-6 pb-2">
                <div class="flex items-center gap-3 mb-2">
                    <div id="authModeIcon" class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-500/25 transition-all duration-300">
                        <svg id="signinIcon" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <svg id="signupIcon" class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="authTitle" class="text-xl font-bold text-slate-900 transition-all duration-300">Staff Portal</h3>
                        <p id="authSubtitle" class="text-sm text-slate-500 transition-all duration-300">Sign in for Admins and Teachers only.</p>
                    </div>
                </div>
            </div>

            <!-- Toggle Switch -->
            <div class="px-6 py-4">
                <div class="relative bg-slate-100 rounded-2xl p-1.5 flex items-center">
                    <div id="toggleSlider" class="absolute left-1.5 w-[calc(50%-6px)] h-[calc(100%-12px)] bg-white rounded-xl shadow-sm transition-all duration-300 ease-out"></div>
                    <button onclick="switchAuthMode('login')" id="loginTab" class="relative z-10 flex-1 py-2.5 text-sm font-semibold text-teal-700 transition-colors duration-300 text-center">
                        Sign In
                    </button>
                    <button onclick="switchAuthMode('register')" id="registerTab" class="relative z-10 flex-1 py-2.5 text-sm font-semibold text-slate-500 transition-colors duration-300 text-center">
                        Pupil Sign Up
                    </button>
                </div>
            </div>

            <!-- Forms Container -->
            <div class="flex-1 p-6 relative overflow-hidden">
                <!-- Login Form -->
                <div id="loginForm" class="form-slide active space-y-5">
                    <form method="POST" action="{{ route('login') }}" class="space-y-5" onsubmit="handleAuthSubmit(event, 'login')">
                        @csrf
                        
                        <div class="space-y-4">
                            <div class="group">
                                <label class="block text-sm font-semibold text-slate-700 mb-2 group-focus-within:text-teal-600 transition-colors">Username</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="username" required
                                           class="input-field w-full pl-12 pr-4 py-3.5 rounded-xl outline-none bg-white border-2 border-slate-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all"
                                           placeholder="Enter your username">
                                </div>
                                @error('username')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1 animate-pulse">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="group">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-semibold text-slate-700 group-focus-within:text-teal-600 transition-colors">Password</label>
                                    <button type="button" onclick="openForgotPasswordModal()" class="text-xs font-medium text-teal-600 hover:text-teal-700 hover:underline transition-colors">
                                        Forgot password?
                                    </button>
                                </div>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input type="password" name="password" id="loginPassword" required
                                           class="input-field w-full pl-12 pr-12 py-3.5 rounded-xl outline-none bg-white border-2 border-slate-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all"
                                           placeholder="Enter your password">
                                    <button type="button" onclick="togglePassword('loginPassword')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 hover:bg-slate-100 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                       

                        <button type="submit" id="loginSubmitBtn" class="w-full btn-primary text-white py-4 rounded-xl font-bold text-base flex items-center justify-center gap-2 shadow-xl shadow-teal-500/30 hover:shadow-teal-500/40 transform hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden group">
                            <span class="btn-shimmer absolute inset-0 opacity-0 transition-opacity duration-300"></span>
                            <span id="loginBtnText" class="relative z-10 transition-all duration-300">Sign In to Portal</span>
                            <svg id="loginSpinner" class="hidden w-5 h-5 relative z-10" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        {{-- Biometric Login Button --}}
                        <div id="biometricLoginContainer" class="hidden">
                            <div class="relative my-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-slate-200"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-slate-500">or</span>
                                </div>
                            </div>
                            <button type="button" id="biometricLoginBtn" onclick="handleBiometricLogin()"
                                    class="w-full py-3.5 rounded-xl border-2 border-slate-200 text-slate-700 font-semibold hover:border-teal-400 hover:text-teal-700 hover:bg-teal-50 transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-fingerprint text-lg"></i>
                                <span>Sign in with Face ID, Fingerprint, or Passkey</span>
                            </button>
                        </div>
                    </form>

                    <!-- Forgot Password Modal -->
                    <div id="forgotPasswordModal" class="fixed inset-0 z-50 hidden" aria-modal="true">
                        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0" id="forgotPasswordBackdrop" onclick="closeForgotPasswordModal()"></div>
                        <div class="absolute inset-0 flex items-center justify-center p-4">
                            <div id="forgotPasswordPanel" class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
                                <!-- Header -->
                                <div class="p-6 bg-gradient-to-r from-teal-50 to-teal-100 border-b border-teal-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-teal-500 text-white flex items-center justify-center">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-teal-900">Forgot Password</h3>
                                            <p class="text-sm text-teal-600">We'll send a reset link to your email</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="closeForgotPasswordModal()" class="w-8 h-8 rounded-lg bg-white border border-teal-200 text-teal-600 hover:text-teal-800 flex items-center justify-center transition-all">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <!-- Body -->
                                <div class="p-6">
                                    <div id="forgotPasswordFormContainer">
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                                            <div class="relative">
                                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                                <input type="email" id="forgotPasswordEmail" required
                                                    class="w-full pl-11 pr-4 py-3 rounded-xl border-2 border-slate-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all"
                                                    placeholder="Enter your registered email">
                                            </div>
                                            <p id="forgotPasswordError" class="mt-2 text-sm text-red-600 hidden"></p>
                                        </div>
                                        <button type="button" onclick="submitForgotPassword()" id="forgotPasswordSubmitBtn"
                                            class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                                            <span id="forgotPasswordBtnText">Send Reset Link</span>
                                            <svg id="forgotPasswordSpinner" class="hidden w-5 h-5" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                                <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="forgotPasswordSuccess" class="hidden text-center py-4">
                                        <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-check text-2xl text-green-600"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-slate-900 mb-2">Check Your Email</h4>
                                        <p class="text-sm text-slate-600">If an account exists with that email, we've sent a password reset link.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Box -->
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-blue-900 mb-1">Need Help?</p>
                                <p class="text-xs text-blue-700 leading-relaxed">Contact the school admin office if you're having trouble signing in.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Registration Form -->
                <div id="registerForm" class="form-slide exit-left space-y-5">
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-100 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-orange-900">Pupil Registration Only</p>
                                <p class="text-xs text-orange-700">This portal is exclusively for enrolled pupils.</p>
                            </div>
                        </div>
                    </div>

                 @if($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- resources/views/auth/register.blade.php -->

<form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden" onsubmit="handleAuthSubmit(event, 'register')">
    @csrf
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-8 py-6 text-white">
        <h2 class="text-2xl font-bold flex items-center gap-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Pupil Registration
        </h2>
        <p class="text-teal-100 mt-1 text-sm">Complete the form below to register a new pupil</p>
    </div>

    <div class="p-8 space-y-8">

        <!-- 1. Academic Information -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-bold">1</span>
                Academic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Grade Level <span class="text-red-500">*</span></label>
                    <select name="grade_level_id" required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all bg-white">
                        <option value="">Select Grade Level</option>
                        @foreach($gradeLevels as $level)
                            <option value="{{ $level->id }}" @selected(old('grade_level_id') == $level->id)>{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Student Type <span class="text-red-500">*</span></label>
                    <select name="type" id="studentType" required onchange="toggleStudentTypeFields()"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all bg-white">
                        <option value="new" @selected(old('type') == 'new' || old('type') === null)>New Pupil</option>
                        <option value="transferee" @selected(old('type') == 'transferee')>Transferee</option>
                        <option value="continuing" @selected(old('type') == 'continuing')>Continuing</option>
                    </select>
                </div>
            </div>
            <div id="previousSchoolField" class="mt-4 hidden">
                <label class="block text-sm font-medium text-slate-700 mb-2">Previous School <span class="text-red-500">*</span></label>
                <input type="text" name="previous_school" id="previousSchoolInput"
                       value="{{ old('previous_school') }}"
                       placeholder="Name of previous school"
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                <p class="text-xs text-slate-500 mt-2">Required for transferee pupils</p>
            </div>
        </div>
        
        <!-- 2. LRN -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-bold">2</span>
                Learner Reference Number (LRN)
            </h3>
            <div id="lrnNewField" class="{{ old('type') === 'transferee' ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-slate-700 mb-2">LRN <span class="text-slate-400 font-normal">(New / Continuing Pupils)</span></label>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-0">
                    <span class="inline-flex items-center justify-center px-4 py-3 bg-teal-50 border-2 border-teal-200 sm:border-r-0 rounded-lg sm:rounded-r-none text-teal-700 font-semibold text-sm w-full sm:w-auto">120231</span>
                    <input type="text" name="lrn_suffix" id="lrn_suffix" maxlength="6" placeholder="000000"
                           value="{{ old('lrn_suffix') }}"
                           class="flex-1 px-4 py-3 border-2 border-slate-200 rounded-lg sm:rounded-l-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all font-mono text-lg tracking-widest text-center sm:text-left placeholder:text-slate-300"
                           minlength="6" pattern="\d{6}" title="Please enter exactly 6 digits">
                </div>
                <p class="text-xs text-slate-500 mt-2">Enter the last 6 digits. Leave blank if not yet available.</p>
            </div>
            <div id="lrnExistingField" class="{{ old('type') === 'transferee' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-slate-700 mb-2">LRN <span class="text-slate-400 font-normal">(Transferees — Full 12-digit LRN)</span></label>
                <input type="text" name="lrn" id="lrn_full" maxlength="12" placeholder="Enter full 12-digit LRN"
                       value="{{ old('lrn') }}"
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all font-mono text-lg tracking-wider placeholder:text-slate-300"
                       minlength="12" pattern="\d{12}" title="Please enter exactly 12 digits">
                <p class="text-xs text-slate-500 mt-2">Enter the full 12-digit LRN from your previous school</p>
            </div>
        </div>

        <!-- 3. Personal Information -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-bold">3</span>
                Personal Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" placeholder="Juan" required
                           value="{{ old('first_name') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" placeholder="Santos" required
                           value="{{ old('last_name') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Middle Name</label>
                    <input type="text" name="middle_name" placeholder="Dela Cruz"
                           value="{{ old('middle_name') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Suffix</label>
                    <select name="suffix" class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all bg-white">
                        <option value="" @selected(old('suffix') == '')>None</option>
                        <option value="Jr." @selected(old('suffix') == 'Jr.')>Jr.</option>
                        <option value="Sr." @selected(old('suffix') == 'Sr.')>Sr.</option>
                        <option value="II" @selected(old('suffix') == 'II')>II</option>
                        <option value="III" @selected(old('suffix') == 'III')>III</option>
                        <option value="IV" @selected(old('suffix') == 'IV')>IV</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Birthdate <span class="text-red-500">*</span></label>
                    <input type="date" name="birthday" required
                           value="{{ old('birthday') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all text-slate-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Place of Birth <span class="text-red-500">*</span></label>
                    <input type="text" name="birth_place" placeholder="City, Province" required
                           value="{{ old('birth_place') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all bg-white">
                        <option value="">Select Gender</option>
                        <option value="Male" @selected(old('gender') == 'Male')>Male</option>
                        <option value="Female" @selected(old('gender') == 'Female')>Female</option>
                        <option value="Other" @selected(old('gender') == 'Other')>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nationality <span class="text-red-500">*</span></label>
                    <input type="text" name="nationality" placeholder="Filipino" required
                           value="{{ old('nationality') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Religion <span class="text-red-500">*</span></label>
                    <input type="text" name="religion" placeholder="Roman Catholic" required
                           value="{{ old('religion') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Ethnicity <span class="text-red-500">*</span></label>
                    <input type="text" name="ethnicity" placeholder="e.g., Cebuano, Tagalog" required
                           value="{{ old('ethnicity') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mother Tongue <span class="text-red-500">*</span></label>
                    <input type="text" name="mother_tongue" placeholder="e.g., Cebuano, Filipino" required
                           value="{{ old('mother_tongue') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
            </div>
        </div>

        <!-- 4. Family Information -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-bold">4</span>
                Family Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Father's Name</label>
                    <input type="text" name="father_name" placeholder="Full Name"
                           value="{{ old('father_name') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Father's Occupation</label>
                    <input type="text" name="father_occupation" placeholder="e.g., Engineer"
                           value="{{ old('father_occupation') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mother's Maiden Name</label>
                    <input type="text" name="mother_name" placeholder="Full Name"
                           value="{{ old('mother_name') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Mother's Occupation</label>
                    <input type="text" name="mother_occupation" placeholder="e.g., Teacher"
                           value="{{ old('mother_occupation') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Guardian's Name <span class="text-red-500">*</span></label>
                    <input type="text" name="guardian_name" placeholder="Full Name" required
                           value="{{ old('guardian_name') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Relationship <span class="text-red-500">*</span></label>
                    <select name="guardian_relationship" required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all bg-white">
                        <option value="">Select</option>
                        <option value="Parent" @selected(old('guardian_relationship') == 'Parent')>Parent</option>
                        <option value="Grandparent" @selected(old('guardian_relationship') == 'Grandparent')>Grandparent</option>
                        <option value="Sibling" @selected(old('guardian_relationship') == 'Sibling')>Sibling</option>
                        <option value="Relative" @selected(old('guardian_relationship') == 'Relative')>Relative</option>
                        <option value="Other" @selected(old('guardian_relationship') == 'Other')>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Guardian Contact</label>
                    <input type="tel" name="guardian_contact" id="guardianContact" maxlength="11" placeholder="09XXXXXXXXX"
                           value="{{ old('guardian_contact') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all font-mono tracking-wider">
                    <p class="text-xs text-slate-500 mt-1">11 digits only</p>
                </div>
                <div class="hidden md:block"></div>
            </div>
        </div>

        <!-- 5. Address -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-bold">5</span>
                Address Information
            </h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Street Address <span class="text-red-500">*</span></label>
                <input type="text" name="street_address" placeholder="House No., Street, Subdivision" required
                       value="{{ old('street_address') }}"
                       class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Barangay <span class="text-red-500">*</span></label>
                    <input type="text" name="barangay" placeholder="Barangay" required
                           value="{{ old('barangay') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" placeholder="City" required
                           value="{{ old('city') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Province <span class="text-red-500">*</span></label>
                    <input type="text" name="province" placeholder="Province" required
                           value="{{ old('province') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">ZIP Code <span class="text-red-500">*</span></label>
                    <input type="text" name="zip_code" placeholder="ZIP" maxlength="4" required
                           value="{{ old('zip_code') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all font-mono">
                </div>
            </div>
        </div>



        <!-- 6. Account & Photo -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-bold">6</span>
                Account Setup & Photo
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" placeholder="juan.santos" required
                           value="{{ old('username') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="regEmail" placeholder="juan@example.com" required
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" placeholder="••••••••" required
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                    <div class="mt-2 bg-amber-50 border border-amber-100 rounded-lg p-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-xs text-amber-800 leading-relaxed">
                                <p class="font-semibold mb-1">Password must contain:</p>
                                <div class="flex flex-wrap gap-x-3 gap-y-1">
                                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Uppercase</span>
                                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Lowercase</span>
                                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Number</span>
                                    <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Special character</span>
                                </div>
                                <p class="mt-1.5 text-amber-700">Example: <code class="bg-white px-1.5 py-0.5 rounded text-amber-600 font-mono font-bold border border-amber-200">@Password123</code></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required
                           class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-3">Pupil Photo</label>
                <div class="flex items-center justify-center w-full">
                    <label for="photo" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mb-2 text-sm text-slate-500 group-hover:text-slate-600"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-slate-400">PNG, JPG or JPEG (MAX. 2MB)</p>
                        </div>
                        <input id="photo" type="file" name="photo" accept="image/*" class="hidden" onchange="previewImage(this)" />
                    </label>
                </div>
                <div id="imagePreview" class="mt-4 hidden">
                    <img src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl border-2 border-teal-200 shadow-md">
                </div>
            </div>
        </div>


        <!-- 7. Remarks -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-bold">7</span>
                Pupil Remarks <span class="text-slate-400 font-normal text-sm">(Optional)</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Remark Code</label>
                    <select name="remarks" class="w-full px-4 py-3 border-2 border-slate-200 rounded-lg focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all bg-white">
                        <option value="" @selected(old('remarks') == '')>-- Select Remark (Optional) --</option>
                        <option value="TI" @selected(old('remarks') == 'TI')>TI - Transferred In</option>
                        <option value="TO" @selected(old('remarks') == 'TO')>TO - Transferred Out</option>
                        <option value="DO" @selected(old('remarks') == 'DO')>DO - Dropped Out</option>
                        <option value="LE" @selected(old('remarks') == 'LE')>LE - Late Enrollee</option>
                        <option value="CCT" @selected(old('remarks') == 'CCT')>CCT - CCT Recipient</option>
                        <option value="BA" @selected(old('remarks') == 'BA')>BA - Balik Aral</option>
                        <option value="LWD" @selected(old('remarks') == 'LWD')>LWD - Learner With Disability</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <div class="p-4 bg-amber-50 border border-amber-100 rounded-lg w-full">
                        <p class="text-xs text-amber-800 leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i>
                            Select only if applicable. Multiple selections require admin assistance.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 8. Documents -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200" id="documentsSection">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-sm font-bold">8</span>
                Required Documents
            </h3>
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span id="documentRequirementsText">New Pupils: Birth Certificate is required.</span>
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Birth Certificate -->
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        <span id="birthCertLabel">Birth Certificate</span>
                        <span class="text-slate-400 text-xs" id="birthCertRequired">(Optional)</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="birth_certificate" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-2 pb-2">
                                <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-slate-500 group-hover:text-slate-600"><span class="font-semibold">Click to upload</span></p>
                                <p class="text-xs text-slate-400">PDF, JPG, PNG (MAX. 5MB)</p>
                            </div>
                            <input id="birth_certificate" type="file" name="birth_certificate" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'birthCertPreview')" />
                        </label>
                    </div>
                    <div id="birthCertPreview" class="mt-2 hidden flex items-center gap-2 text-sm text-teal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="file-name"></span>
                    </div>
                </div>
                <!-- Report Card -->
                <div class="bg-white p-4 rounded-lg border border-slate-200 hidden" id="reportCardField">
                    <label class="block text-sm font-medium text-slate-700 mb-2" id="reportCardLabel">
                        Report Card / Form 138 <span class="text-slate-400 text-xs">(Optional)</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="report_card" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-2 pb-2">
                                <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-slate-500 group-hover:text-slate-600"><span class="font-semibold">Click to upload</span></p>
                                <p class="text-xs text-slate-400">PDF, JPG, PNG (MAX. 5MB)</p>
                            </div>
                            <input id="report_card" type="file" name="report_card" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'reportCardPreview')" />
                        </label>
                    </div>
                    <div id="reportCardPreview" class="mt-2 hidden flex items-center gap-2 text-sm text-teal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="file-name"></span>
                    </div>
                </div>
                <!-- Good Moral -->
                <div class="bg-white p-4 rounded-lg border border-slate-200 hidden" id="goodMoralField">
                    <label class="block text-sm font-medium text-slate-700 mb-2" id="goodMoralLabel">
                        Certificate of Good Moral Character <span class="text-slate-400 text-xs">(Optional)</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="good_moral" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-2 pb-2">
                                <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-slate-500 group-hover:text-slate-600"><span class="font-semibold">Click to upload</span></p>
                                <p class="text-xs text-slate-400">PDF, JPG, PNG (MAX. 5MB)</p>
                            </div>
                            <input id="good_moral" type="file" name="good_moral" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'goodMoralPreview')" />
                        </label>
                    </div>
                    <div id="goodMoralPreview" class="mt-2 hidden flex items-center gap-2 text-sm text-teal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="file-name"></span>
                    </div>
                </div>
                <!-- Transfer Credentials -->
                <div class="bg-white p-4 rounded-lg border border-slate-200 hidden" id="transferCredentialField">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Transfer Credentials / Honorable Dismissal <span class="text-slate-400 text-xs">(Optional)</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="transfer_credential" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-2 pb-2">
                                <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm text-slate-500 group-hover:text-slate-600"><span class="font-semibold">Click to upload</span></p>
                                <p class="text-xs text-slate-400">PDF, JPG, PNG (MAX. 5MB)</p>
                            </div>
                            <input id="transfer_credential" type="file" name="transfer_credential" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'transferCredPreview')" />
                        </label>
                    </div>
                    <div id="transferCredPreview" class="mt-2 hidden flex items-center gap-2 text-sm text-teal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="file-name"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" id="regSubmitBtn" class="w-full bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg shadow-teal-500/30 hover:shadow-xl hover:shadow-teal-500/40 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3 relative overflow-hidden group">
                <span class="btn-shimmer absolute inset-0 opacity-0 transition-opacity duration-300"></span>
                <svg id="regBtnIcon" class="w-6 h-6 relative z-10 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="regBtnText" class="relative z-10 transition-all duration-300">Complete Registration</span>
                <svg id="regSpinner" class="hidden w-5 h-5 relative z-10" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
            <p class="text-center text-sm text-slate-500 mt-4">
                By registering, you agree to our <a href="javascript:void(0)" onclick="openModal('termsOfUseModal')" class="text-teal-600 hover:underline font-medium">Terms of Service</a> and <a href="javascript:void(0)" onclick="openModal('privacyPolicyModal')" class="text-teal-600 hover:underline font-medium">Privacy Policy</a>
            </p>
        </div>
    </div>
</form>

<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>



                    <!-- Verification Note -->
                    <div class="mt-4 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-amber-900 mb-1">Account Verification Required</p>
                                <p class="text-xs text-amber-800 leading-relaxed">Your account will be reviewed by the school admin before activation. You'll receive a notification once approved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                <p class="text-xs text-center text-slate-500 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Secured by Tugawe ES Admin • DepEd Negros Oriental
                </p>
            </div>
        </div>
    </div>

    <!-- All Announcements Modal -->
    <div id="allAnnouncementsModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('allAnnouncementsModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-3xl max-h-[85vh] overflow-y-auto rounded-3xl shadow-2xl">
                <div class="sticky top-0 bg-white border-b border-slate-100 p-4 sm:p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">All Announcements</h2>
                        <p class="text-sm text-slate-500">Stay updated with school news</p>
                    </div>
                    <button onclick="closeModal('allAnnouncementsModal')" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    @foreach($announcements as $announcement)
                    <article class="pb-6 border-b border-slate-100 last:border-0 hover:bg-slate-50 p-4 rounded-xl transition-colors">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold">News</span>
                            <time class="text-sm text-slate-400">
                                {{ $announcement->created_at->format('F d, Y') }}
                            </time>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $announcement->title }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ $announcement->message }}</p>
                        <div class="mt-4 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center text-xs font-bold text-teal-700">
                                {{ $announcement->author ? substr($announcement->author->full_name ?? $announcement->author->name ?? 'A', 0, 1) : 'A' }}
                            </div>
                            @php
                                $authorName2 = $announcement->author?->full_name ?? $announcement->author?->name ?? 'Administrator';
                                $authorRole2 = 'Administrator';
                                if ($announcement->author && $announcement->author->role) {
                                    $roleName2 = strtolower($announcement->author->role->name ?? '');
                                    if ($roleName2 === 'teacher' && $announcement->author->teacher) {
                                        $teacherSections2 = $announcement->author->teacher->sections;
                                        if ($teacherSections2->isNotEmpty()) {
                                            $firstSection2 = $teacherSections2->first();
                                            $gradeName2 = $firstSection2->gradeLevel?->name ?? '';
                                            $authorRole2 = $gradeName2 ? $gradeName2 . ' Adviser' : 'Teacher';
                                        } else {
                                            $authorRole2 = 'Teacher';
                                        }
                                    } elseif ($roleName2 === 'admin') {
                                        $authorRole2 = 'Administrator';
                                    }
                                }
                            @endphp
                            <span class="text-sm text-slate-500">Posted by <span class="font-semibold text-slate-700">{{ $authorName2 }}</span> <span class="text-slate-400">· {{ $authorRole2 }}</span></span>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Terms & Process Modal -->
    <div id="enrollTermsModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('enrollTermsModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-3xl shadow-2xl flex flex-col">
                <div class="sticky top-0 bg-white border-b border-slate-100 p-4 sm:p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Enrollment Guidelines</h2>
                        <p class="text-sm text-slate-500">Please read before proceeding</p>
                    </div>
                    <button onclick="closeModal('enrollTermsModal')" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <!-- Enrollment Process -->
                    <div class="bg-teal-50 border border-teal-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-teal-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Enrollment Process
                        </h3>
                        <ol class="space-y-3 text-sm text-teal-900">
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-teal-200 text-teal-800 flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>
                                <span>Fill out the online registration form with accurate personal and guardian information.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-teal-200 text-teal-800 flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                                <span>Upload the required documents (Birth Certificate, Report Card, Good Moral Certificate).</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-teal-200 text-teal-800 flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>
                                <span>Wait for the school administrator to review and verify your application.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-teal-200 text-teal-800 flex items-center justify-center text-xs font-bold flex-shrink-0">4</span>
                                <span>Check your account status. You will receive a notification once approved.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-6 h-6 rounded-full bg-teal-200 text-teal-800 flex items-center justify-center text-xs font-bold flex-shrink-0">5</span>
                                <span>Visit the school office to complete the enrollment and submit original documents if required.</span>
                            </li>
                        </ol>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Terms & Conditions
                        </h3>
                        <ul class="space-y-3 text-sm text-slate-700">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                All information provided must be true and accurate. False information may result in disqualification.
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Uploaded documents must be clear, legible, and in the accepted formats (PDF, JPG, PNG).
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Account approval is subject to the school administrator's verification and availability of slots.
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Parents or guardians are responsible for the pupil's attendance, behavior, and compliance with school policies.
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Personal data collected will be used solely for enrollment and school record purposes in accordance with DepEd guidelines.
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                The school reserves the right to accept or decline enrollment applications based on policy and capacity.
                            </li>
                        </ul>
                    </div>

                    <!-- Select Student Type -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Select Your Student Type
                        </h3>
                        <p class="text-sm text-slate-500 mb-4">Choose the option that applies to you. This will determine where you need to go next:</p>
                        <div class="space-y-3">
                            <label class="cursor-pointer block">
                                <input type="radio" name="studentTypeSelect" value="new" class="peer hidden" onchange="toggleProceedBtn()">
                                <div class="flex items-start gap-4 p-4 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 peer-checked:bg-emerald-500 text-emerald-600 peer-checked:text-white flex items-center justify-center text-sm font-bold flex-shrink-0 transition-colors">
                                        N
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 peer-checked:text-emerald-900">New Pupil</p>
                                        <p class="text-sm text-slate-500 peer-checked:text-emerald-700 leading-relaxed">Enrolling for the <strong>first time</strong>. You will be redirected to the <strong>Registration Form</strong> to create a new account.</p>
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer block">
                                <input type="radio" name="studentTypeSelect" value="transferee" class="peer hidden" onchange="toggleProceedBtn()">
                                <div class="flex items-start gap-4 p-4 rounded-xl border-2 border-slate-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all">
                                    <div class="w-10 h-10 rounded-full bg-orange-100 peer-checked:bg-orange-500 text-orange-600 peer-checked:text-white flex items-center justify-center text-sm font-bold flex-shrink-0 transition-colors">
                                        T
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 peer-checked:text-orange-900">Transferee</p>
                                        <p class="text-sm text-slate-500 peer-checked:text-orange-700 leading-relaxed">Coming <strong>from another school</strong>. You will be redirected to the <strong>Registration Form</strong> to create a new account.</p>
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer block">
                                <input type="radio" name="studentTypeSelect" value="continuing" class="peer hidden" onchange="toggleProceedBtn()">
                                <div class="flex items-start gap-4 p-4 rounded-xl border-2 border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 peer-checked:bg-blue-500 text-blue-600 peer-checked:text-white flex items-center justify-center text-sm font-bold flex-shrink-0 transition-colors">
                                        C
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 peer-checked:text-blue-900">Continuing</p>
                                        <p class="text-sm text-slate-500 peer-checked:text-blue-700 leading-relaxed">Previously enrolled at Tugawe ES, moving to the <strong>next grade level</strong>. You will be redirected to the <strong>Login Form</strong> to sign in with your existing account.</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Agreement Checkbox -->
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" id="agreeTerms" class="custom-checkbox mt-0.5" onchange="toggleProceedBtn()">
                            <span class="text-sm text-amber-900">
                                I have read and understood the <strong>enrollment process</strong>, selected my <strong>student type</strong>, and agree to the <strong>terms and conditions</strong> stated above. I confirm that all information I will provide is accurate and true.
                            </span>
                        </label>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-100 bg-white sticky bottom-0">
                    <button onclick="handleEnrollProceed()" id="proceedEnrollBtn" class="w-full btn-primary text-white py-4 rounded-xl font-bold text-base flex items-center justify-center gap-2 shadow-xl shadow-teal-500/30 opacity-50 pointer-events-none transition-all duration-300">
                        <span id="proceedBtnText">Select student type to proceed</span>
                        <svg id="proceedBtnIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Approval Modal -->
    <div id="pendingApprovalModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('pendingApprovalModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-8 text-center">
                    <div class="w-20 h-20 mx-auto bg-amber-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Application Pending</h2>
                    <p class="text-slate-600 leading-relaxed">Your registration is currently under review by the school administrator.</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-blue-900 mb-1">What happens next?</p>
                                <p class="text-xs text-blue-700 leading-relaxed">The admin will verify your documents and information. You'll be able to sign in once your account is approved.</p>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeModal('pendingApprovalModal')" class="w-full btn-primary text-white py-3.5 rounded-xl font-bold text-base shadow-lg shadow-teal-500/30 hover:shadow-teal-500/40 transform hover:-translate-y-0.5 transition-all duration-200">
                        I Understand
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Modal -->
    <div id="locationModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('locationModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-4xl max-h-[90vh] overflow-hidden rounded-3xl shadow-2xl flex flex-col">
                <div class="sticky top-0 bg-white border-b border-slate-100 p-4 sm:p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">School Location</h2>
                        <p class="text-sm text-slate-500">Tugawe, Dauin, Negros Oriental, Philippines</p>
                    </div>
                    <button onclick="closeModal('locationModal')" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 min-h-[400px] relative">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3563.9846177980307!2d123.26033327450058!3d9.21464268605648!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33ab430c59411a49%3A0xa072deccc8e36750!2sTugawe%20Elementary%20School!5e1!3m2!1sen!2sph!4v1776898197524!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        width="100%" 
                        height="100%" 
                        style="border:0; min-height: 450px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="absolute inset-0">
                    </iframe>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Tugawe Elementary School</p>
                            <p class="text-sm text-slate-500">Brgy. Tugawe, Dauin, Negros Oriental, Philippines</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty Modal -->
    <div id="facultyModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('facultyModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-5xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-2xl">
                <div class="sticky top-0 bg-white border-b border-slate-100 p-4 sm:p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Faculty & Staff</h2>
                        <p class="text-sm text-slate-500">Tugawe Elementary School</p>
                    </div>
                    <button onclick="closeModal('facultyModal')" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if($teachers->count())
                <div class="p-8 space-y-12">
                    @if($principal)
                    <div class="text-center pb-12 border-b border-slate-100">
                        <div class="relative inline-block mb-6">
                            <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-orange-400 rounded-3xl rotate-3 opacity-20"></div>
                            <div class="relative w-32 h-32 mx-auto rounded-3xl overflow-hidden bg-slate-100 shadow-xl border-4 border-white">
                                <img src="{{ $principal->user?->photo ? profile_photo_url($principal->user->photo) : asset('images/photo-placeholder.png') }}" 
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-teal-500 text-white p-2 rounded-xl shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900">{{ $principal->first_name }} {{ $principal->last_name }}</h3>
                        <p class="text-teal-600 font-semibold">School Principal</p>
                    </div>
                    @endif

                    @if($teachingStaff->count())
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-8 text-center">Teaching Staff</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                            @foreach($teachingStaff as $teacher)
                            <div class="text-center group">
                                <div class="relative mb-4 mx-auto w-20 h-20">
                                    <div class="absolute inset-0 bg-gradient-to-br from-teal-200 to-orange-200 rounded-2xl rotate-3 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <div class="relative w-20 h-20 mx-auto rounded-2xl overflow-hidden bg-slate-100 shadow-md border-2 border-white">
                                        <img src="{{ $teacher->user?->photo ? profile_photo_url($teacher->user->photo) : asset('images/photo-placeholder.png') }}"
                                             class="w-full h-full object-cover"
                                             loading="lazy">
                                    </div>
                                </div>
                                <h5 class="text-sm font-bold text-slate-900 mb-1">{{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
                                <p class="text-xs text-teal-600 font-semibold bg-teal-50 px-2 py-0.5 rounded-full inline-block mb-1">{{ $teacher->position ?? 'Teacher' }}</p>
                                @php
                                    $teacherGradeLevels = $teacher->sections->pluck('gradeLevel.name')->filter()->unique()->values();
                                @endphp
                                @if($teacherGradeLevels->count())
                                    <p class="text-[10px] text-slate-500">
                                        {{ $teacherGradeLevels->implode(', ') }}
                                    </p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyPolicyModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('privacyPolicyModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-3xl shadow-2xl flex flex-col">
                <div class="sticky top-0 bg-white border-b border-slate-100 p-4 sm:p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Privacy Policy</h2>
                        <p class="text-sm text-slate-500">How we protect your information</p>
                    </div>
                    <button onclick="closeModal('privacyPolicyModal')" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <div class="bg-teal-50 border border-teal-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-teal-800 mb-3">Data We Collect</h3>
                        <p class="text-sm text-teal-900 leading-relaxed">
                            Tugawe Elementary School collects personal information including student names, birthdates, addresses, guardian details, and academic records solely for enrollment and school administration purposes. All data is stored securely and accessed only by authorized personnel.
                        </p>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-blue-800 mb-3">How We Use Your Data</h3>
                        <ul class="space-y-2 text-sm text-blue-900">
                            <li class="flex items-start gap-2"><span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Processing student enrollment and registration</li>
                            <li class="flex items-start gap-2"><span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Maintaining academic records and grades</li>
                            <li class="flex items-start gap-2"><span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Communicating with parents/guardians</li>
                            <li class="flex items-start gap-2"><span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Compliance with Department of Education requirements</li>
                        </ul>
                    </div>
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-amber-800 mb-3">Data Protection</h3>
                        <p class="text-sm text-amber-900 leading-relaxed">
                            We implement appropriate security measures to protect against unauthorized access, alteration, or destruction of personal data. Your information will never be sold or shared with third parties for commercial purposes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms of Use Modal -->
    <div id="termsOfUseModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('termsOfUseModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-3xl shadow-2xl flex flex-col">
                <div class="sticky top-0 bg-white border-b border-slate-100 p-4 sm:p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Terms of Use</h2>
                        <p class="text-sm text-slate-500">Rules and guidelines for using our services</p>
                    </div>
                    <button onclick="closeModal('termsOfUseModal')" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <div class="bg-teal-50 border border-teal-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-teal-800 mb-3">Acceptance of Terms</h3>
                        <p class="text-sm text-teal-900 leading-relaxed">
                            By accessing and using the Tugawe Elementary School online portal, you agree to comply with these Terms of Use. If you do not agree with any part of these terms, please discontinue use of the platform immediately.
                        </p>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-blue-800 mb-3">User Responsibilities</h3>
                        <ul class="space-y-2 text-sm text-blue-900">
                            <li class="flex items-start gap-2"><span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Provide accurate and truthful information during registration</li>
                            <li class="flex items-start gap-2"><span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Maintain the confidentiality of your account credentials</li>
                            <li class="flex items-start gap-2"><span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Use the platform only for legitimate school-related purposes</li>
                            <li class="flex items-start gap-2"><span class="mt-1 w-1.5 h-1.5 rounded-full bg-blue-400 flex-shrink-0"></span>Do not share your login details with others</li>
                        </ul>
                    </div>
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-amber-800 mb-3">Account Security</h3>
                        <p class="text-sm text-amber-900 leading-relaxed">
                            Users are responsible for maintaining the security of their accounts. Any suspicious activity should be reported to the school administration immediately. The school reserves the right to suspend accounts that violate these terms.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div id="contactModal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('contactModal')"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-lg max-h-[90vh] overflow-hidden rounded-3xl shadow-2xl flex flex-col">
                <div class="sticky top-0 bg-white border-b border-slate-100 p-4 sm:p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Contact Us</h2>
                        <p class="text-sm text-slate-500">Get in touch with Tugawe Elementary School</p>
                    </div>
                    <button onclick="closeModal('contactModal')" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <div class="bg-teal-50 border border-teal-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-teal-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            School Address
                        </h3>
                        <p class="text-sm text-teal-900 leading-relaxed">
                            Barangay Tugawe, Dauin, Negros Oriental, Philippines
                        </p>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email
                        </h3>
                        <p class="text-sm text-blue-900 leading-relaxed">
                            tugaweelementschool@gmail.com
                        </p>
                    </div>
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-amber-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            School Hours
                        </h3>
                        <p class="text-sm text-amber-900 leading-relaxed">
                            Monday – Friday: 7:00 AM – 4:00 PM<br>
                            Office is closed on weekends and public holidays.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>


function toggleStudentTypeFields() {
    const typeSelect = document.getElementById('studentType');
    const previousSchoolField = document.getElementById('previousSchoolField');
    const previousSchoolInput = document.getElementById('previousSchoolInput');
    const transferCredentialField = document.getElementById('transferCredentialField');
    const transferCredentialInput = document.getElementById('transfer_credential');
    const documentRequirementsText = document.getElementById('documentRequirementsText');
    const birthCertLabel = document.getElementById('birthCertLabel');
    const birthCertRequired = document.getElementById('birthCertRequired');
    const lrnNewField = document.getElementById('lrnNewField');
    const lrnExistingField = document.getElementById('lrnExistingField');
    const lrnSuffixInput = document.getElementById('lrn_suffix');
    const lrnFullInput = document.getElementById('lrn_full');

    // Document inputs
    const birthCertInput = document.getElementById('birth_certificate');
    const reportCardInput = document.getElementById('report_card');
    const goodMoralInput = document.getElementById('good_moral');
    const transferCredInput = document.getElementById('transfer_credential');

    // Report card and good moral labels
    const reportCardLabel = document.getElementById('reportCardLabel');
    const goodMoralLabel = document.getElementById('goodMoralLabel');
    const transferCredLabel = transferCredentialField.querySelector('label');

    function setRequired(input, label, isRequired) {
        if (!label) return;
        if (isRequired) {
            input.required = true;
            const span = label.querySelector('span.text-slate-400');
            if (span) span.textContent = '(Required)';
        } else {
            input.required = false;
            const span = label.querySelector('span.text-slate-400');
            if (span) span.textContent = '(Optional)';
        }
    }

    // Document field containers
    const reportCardField = document.getElementById('reportCardField');
    const goodMoralField = document.getElementById('goodMoralField');

    if (typeSelect.value === 'transferee') {
        // Show transferee fields
        previousSchoolField.classList.remove('hidden');
        previousSchoolInput.required = true;
        transferCredentialField.classList.remove('hidden');
        reportCardField.classList.remove('hidden');
        goodMoralField.classList.remove('hidden');
        documentRequirementsText.textContent = 'Transferees: Birth Certificate, Report Card, Good Moral, and Transfer Credentials are required.';
        birthCertLabel.textContent = 'Birth Certificate';
        birthCertRequired.classList.remove('hidden');
        birthCertRequired.textContent = '(Required)';
        setRequired(birthCertInput, birthCertInput.closest('.bg-white').querySelector('label'), true);
        setRequired(reportCardInput, reportCardLabel, true);
        setRequired(goodMoralInput, goodMoralLabel, true);
        setRequired(transferCredInput, transferCredLabel, true);
        // Show full LRN input, hide suffix input
        lrnNewField.classList.add('hidden');
        lrnExistingField.classList.remove('hidden');
        lrnSuffixInput.value = '';
    } else if (typeSelect.value === 'new') {
        // Show new student fields
        previousSchoolField.classList.add('hidden');
        previousSchoolInput.required = false;
        previousSchoolInput.value = '';
        transferCredentialField.classList.add('hidden');
        transferCredentialInput.value = '';
        reportCardField.classList.add('hidden');
        goodMoralField.classList.add('hidden');
        documentRequirementsText.textContent = 'New Pupils: Birth Certificate is required.';
        birthCertLabel.textContent = 'Birth Certificate';
        birthCertRequired.classList.remove('hidden');
        birthCertRequired.textContent = '(Required)';
        setRequired(birthCertInput, birthCertInput.closest('.bg-white').querySelector('label'), true);
        setRequired(reportCardInput, reportCardLabel, false);
        setRequired(goodMoralInput, goodMoralLabel, false);
        setRequired(transferCredInput, transferCredLabel, false);
        // Show suffix LRN input, hide full LRN input
        lrnNewField.classList.remove('hidden');
        lrnExistingField.classList.add('hidden');
        lrnFullInput.value = '';
    } else {
        // Continuing students
        previousSchoolField.classList.add('hidden');
        previousSchoolInput.required = false;
        previousSchoolInput.value = '';
        transferCredentialField.classList.add('hidden');
        transferCredentialInput.value = '';
        reportCardField.classList.remove('hidden');
        goodMoralField.classList.remove('hidden');
        documentRequirementsText.textContent = 'Continuing Pupils: All documents are optional.';
        birthCertLabel.textContent = 'Birth Certificate';
        birthCertRequired.classList.remove('hidden');
        birthCertRequired.textContent = '(Optional)';
        setRequired(birthCertInput, birthCertInput.closest('.bg-white').querySelector('label'), false);
        setRequired(reportCardInput, reportCardLabel, false);
        setRequired(goodMoralInput, goodMoralLabel, false);
        setRequired(transferCredInput, transferCredLabel, false);
        // Continuing: same as new — prefix + suffix LRN input
        lrnNewField.classList.remove('hidden');
        lrnExistingField.classList.add('hidden');
        lrnFullInput.value = '';
    }
}

// Document preview function
function previewDocument(input, previewId) {
    const preview = document.getElementById(previewId);
    const fileName = preview.querySelector('.file-name');
    
    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
}

// Run on page load to handle old input
document.addEventListener('DOMContentLoaded', function() {
    toggleStudentTypeFields();
});



document.addEventListener('DOMContentLoaded', function () {
    const suffixInput = document.getElementById('lrn_suffix');
    if (suffixInput) {
        suffixInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    const fullLrnInput = document.getElementById('lrn_full');
    if (fullLrnInput) {
        fullLrnInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    const guardianContact = document.getElementById('guardianContact');
    if (guardianContact) {
        guardianContact.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
        });
    }
});
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }



        function closeAuthPanel() {
            const panel = document.getElementById('authSidePanel');
            const overlay = document.getElementById('sidePanelOverlay');
            
            panel.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        function switchAuthMode(mode) {
            const slider = document.getElementById('toggleSlider');
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            
            // Update toggle slider
            if (mode === 'register') {
                slider.style.transform = 'translateX(100%)';
                loginTab.classList.remove('text-teal-700');
                loginTab.classList.add('text-slate-500');
                registerTab.classList.remove('text-slate-500');
                registerTab.classList.add('text-orange-600');
                
                // Update header
                document.getElementById('authTitle').textContent = 'Student Registration';
                document.getElementById('authSubtitle').textContent = 'Create your student account';
                document.getElementById('signinIcon').classList.add('hidden');
                document.getElementById('signupIcon').classList.remove('hidden');
                document.getElementById('authModeIcon').classList.remove('from-teal-500', 'to-teal-600');
                document.getElementById('authModeIcon').classList.add('from-orange-500', 'to-orange-600');
                
                // Switch forms
                loginForm.classList.remove('active');
                loginForm.classList.add('exit-left');
                registerForm.classList.add('active');
                registerForm.classList.remove('exit-left');
            } else {
                slider.style.transform = 'translateX(0)';
                loginTab.classList.add('text-teal-700');
                loginTab.classList.remove('text-slate-500');
                registerTab.classList.add('text-slate-500');
                registerTab.classList.remove('text-orange-600');
                
                // Update header
                document.getElementById('authTitle').textContent = 'Welcome Back';
                document.getElementById('authSubtitle').textContent = 'Sign in to access your account';
                document.getElementById('signinIcon').classList.remove('hidden');
                document.getElementById('signupIcon').classList.add('hidden');
                document.getElementById('authModeIcon').classList.add('from-teal-500', 'to-teal-600');
                document.getElementById('authModeIcon').classList.remove('from-orange-500', 'to-orange-600');
                
                // Switch forms
                registerForm.classList.remove('active');
                registerForm.classList.add('exit-left');
                loginForm.classList.add('active');
                loginForm.classList.remove('exit-left');
            }
        }

        // Password Toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
        }

        function openAuthPanel(mode = 'login') {
            const panel = document.getElementById('authSidePanel');
            const overlay = document.getElementById('sidePanelOverlay');
            
            panel.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            if (mode === 'register') {
                switchAuthMode('register');
            } else {
                switchAuthMode('login');
            }
        }

        // Form Submit Handler
        function handleAuthSubmit(event, type) {
            const btn = type === 'login' ? document.getElementById('loginSubmitBtn') : document.getElementById('regSubmitBtn');
            const text = type === 'login' ? document.getElementById('loginBtnText') : document.getElementById('regBtnText');
            const spinner = type === 'login' ? document.getElementById('loginSpinner') : document.getElementById('regSpinner');
            const icon = type === 'register' ? document.getElementById('regBtnIcon') : null;
            
            btn.disabled = true;
            btn.classList.add('is-loading');
            text.textContent = type === 'login' ? 'Signing in...' : 'Creating account...';
            
            // Smooth transition: fade out icon, fade in spinner
            if (icon) {
                icon.style.opacity = '0';
                icon.style.transform = 'scale(0.8)';
                setTimeout(() => icon.classList.add('hidden'), 200);
            }
            
            spinner.classList.remove('hidden');
            spinner.style.opacity = '0';
            spinner.style.transform = 'scale(0.8)';
            // Trigger reflow
            void spinner.offsetWidth;
            spinner.style.opacity = '1';
            spinner.style.transform = 'scale(1)';
            
            // Allow normal form submission so file uploads are included
            return true;
        }

        // Send OTP for registration
        // Enrollment Terms Toggle
        function toggleProceedBtn() {
            const checkbox = document.getElementById('agreeTerms');
            const btn = document.getElementById('proceedEnrollBtn');
            const btnText = document.getElementById('proceedBtnText');
            const btnIcon = document.getElementById('proceedBtnIcon');
            const selectedType = document.querySelector('input[name="studentTypeSelect"]:checked');

            if (checkbox.checked && selectedType) {
                btn.classList.remove('opacity-50', 'pointer-events-none');
                btnIcon.classList.remove('hidden');
                
                if (selectedType.value === 'continuing') {
                    btnText.textContent = "Sign In to Enroll";
                } else {
                    btnText.textContent = "Proceed to Registration";
                }
            } else {
                btn.classList.add('opacity-50', 'pointer-events-none');
                btnIcon.classList.add('hidden');
                btnText.textContent = "Select student type and agree to proceed";
            }
        }

        function handleEnrollProceed() {
            const selectedType = document.querySelector('input[name="studentTypeSelect"]:checked');
            if (!selectedType) return;
            
            closeModal('enrollTermsModal');
            
            if (selectedType.value === 'continuing') {
                openAuthPanel('login');
            } else {
                window.location.href = "{{ route('register') }}";
            }
        }

        // Modal Functions
        function openModal(id) {
            closeAuthPanel();
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAuthPanel();
                ['allAnnouncementsModal', 'facultyModal', 'locationModal', 'enrollTermsModal', 'privacyPolicyModal', 'termsOfUseModal', 'contactModal'].forEach(id => closeModal(id));
            }
        });

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    document.getElementById('mobileMenu').classList.add('hidden');
                }
            });
        });

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(20px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(section);
        });

        // Sound effects — same as School Year Closure admin page
        let _audioCtx = null;

        function _getAudioCtx() {
            if (!_audioCtx) {
                _audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (_audioCtx.state === 'suspended') {
                _audioCtx.resume();
            }
            return _audioCtx;
        }

        // Initialize audio on first user interaction (required by some browsers)
        document.addEventListener('click', function _initAudioOnce() {
            _getAudioCtx();
            document.removeEventListener('click', _initAudioOnce);
        }, { once: true });

        function playSuccessSound() {
            try {
                const ctx = _getAudioCtx();
                const now = ctx.currentTime;
                const duration = 2.0;

                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, now);
                osc.frequency.exponentialRampToValueAtTime(1760, now + 0.1);

                gain.gain.setValueAtTime(0, now);
                gain.gain.linearRampToValueAtTime(0.25, now + 0.05);
                gain.gain.setValueAtTime(0.25, now + 0.1);
                gain.gain.exponentialRampToValueAtTime(0.001, now + duration);

                osc.start(now);
                osc.stop(now + duration);
            } catch (e) {
                // Silently fail if audio is blocked or unsupported
            }
        }

        function playErrorSound() {
            try {
                const ctx = _getAudioCtx();
                const now = ctx.currentTime;
                const duration = 2.0;
                const interval = 0.4;

                // First beep
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(400, now);
                gain1.gain.setValueAtTime(0, now);
                gain1.gain.linearRampToValueAtTime(0.25, now + 0.02);
                gain1.gain.setValueAtTime(0.25, now + 0.1);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + interval);
                osc1.start(now);
                osc1.stop(now + interval);

                // Second beep
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(300, now + interval);
                gain2.gain.setValueAtTime(0, now + interval);
                gain2.gain.linearRampToValueAtTime(0.25, now + interval + 0.02);
                gain2.gain.setValueAtTime(0.25, now + interval + 0.1);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + interval * 2);
                osc2.start(now + interval);
                osc2.stop(now + interval * 2);

                // Third beep
                const osc3 = ctx.createOscillator();
                const gain3 = ctx.createGain();
                osc3.connect(gain3);
                gain3.connect(ctx.destination);
                osc3.type = 'sine';
                osc3.frequency.setValueAtTime(200, now + interval * 2);
                gain3.gain.setValueAtTime(0, now + interval * 2);
                gain3.gain.linearRampToValueAtTime(0.25, now + interval * 2 + 0.02);
                gain3.gain.setValueAtTime(0.25, now + interval * 2 + 0.3);
                gain3.gain.exponentialRampToValueAtTime(0.001, now + duration);
                osc3.start(now + interval * 2);
                osc3.stop(now + duration);
            } catch (e) {
                // Silently fail if audio is blocked or unsupported
            }
        }
    </script>

    @if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        playSuccessSound();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            confirmButtonColor: '#0d9488',
            background: '#ffffff',
            color: '#1e293b',
            iconColor: '#0d9488'
        });
    </script>
    @endif

    @if(session('error') || $errors->has('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        playErrorSound();
        Swal.fire({
            icon: 'error',
            title: 'Registration Failed',
            text: "{{ session('error') ?? $errors->first('error') }}",
            confirmButtonColor: '#dc2626',
            background: '#ffffff',
            color: '#1e293b',
            iconColor: '#dc2626'
        });
    </script>
    @endif

@php
$loginOnlyErrors = ['username', 'password'];
$hasRegistrationErrors = false;
foreach($errors->keys() as $key) {
    if (!in_array($key, $loginOnlyErrors)) {
        $hasRegistrationErrors = true;
        break;
    }
}
@endphp

@if($hasRegistrationErrors || session('panel_mode') === 'register')
<script>
document.addEventListener('DOMContentLoaded', function() {
    playErrorSound();
    openAuthPanel('register');
});
</script>
@endif

@if(session('pending_approval'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    openModal('pendingApprovalModal');
});
</script>
@endif

<script src="{{ asset('js/pwa/biometric-auth.js') }}"></script>

{{-- CSRF Token Auto-Refresh (prevents 419 Page Expired on long-open login pages) --}}
<script>
(function() {
    async function refreshCsrfToken() {
        try {
            const response = await fetch('/csrf-token');
            if (!response.ok) return;
            const data = await response.json();
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta && data.token) {
                meta.content = data.token;
                // Also update any hidden _token inputs in forms
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.token;
                });
            }
        } catch (e) {
            console.log('CSRF refresh failed');
        }
    }
    // Refresh every 8 minutes (Laravel default session lifetime is usually 120 min,
    // but CSRF tokens can rotate earlier. 8 min is a safe interval.)
    setInterval(refreshCsrfToken, 8 * 60 * 1000);
    // Also refresh when user comes back to the tab
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            refreshCsrfToken();
        }
    });
})();
</script>

{{-- Biometric Login Handler --}}
<script>
async function checkBiometricLoginAvailable() {
    if (!window.PublicKeyCredential) return;
    try {
        const available = await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();
        if (available) {
            const container = document.getElementById('biometricLoginContainer');
            if (container) container.classList.remove('hidden');
        }
    } catch (e) {
        console.log('Biometric not available');
    }
}

async function handleBiometricLogin() {
    const btn = document.getElementById('biometricLoginBtn');
    const usernameInput = document.querySelector('input[name="username"]');
    const username = usernameInput ? usernameInput.value.trim() : '';

    if (!username) {
        btn.innerHTML = '<i class="fas fa-fingerprint text-lg"></i><span>Please enter your username first</span>';
        btn.classList.add('border-red-300', 'text-red-600');
        usernameInput?.focus();
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-fingerprint text-lg"></i><span>Sign in with Face ID, Fingerprint, or Passkey</span>';
            btn.classList.remove('border-red-300', 'text-red-600');
        }, 2000);
        return;
    }

    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i><span>Verifying...</span>';
    btn.disabled = true;

    try {
        const result = await window.authenticateWithBiometric(username);
        if (result.success && result.redirect) {
            btn.innerHTML = '<i class="fas fa-check text-lg"></i><span>Success!</span>';
            btn.classList.add('border-green-500', 'text-green-700', 'bg-green-50');
            setTimeout(() => window.location.href = result.redirect, 500);
        }
    } catch (error) {
        console.error('Face ID / Fingerprint / Passkey login error:', error);
        btn.innerHTML = '<i class="fas fa-fingerprint text-lg"></i><span>' + (error.message || 'Face ID / Fingerprint / Passkey login failed') + '</span>';
        btn.classList.add('border-red-300', 'text-red-600');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('border-red-300', 'text-red-600');
            btn.disabled = false;
        }, 4000);
    }
}

// Check biometric availability on page load
document.addEventListener('DOMContentLoaded', checkBiometricLoginAvailable);

// Forgot Password Modal
function openForgotPasswordModal() {
    const modal = document.getElementById('forgotPasswordModal');
    const backdrop = document.getElementById('forgotPasswordBackdrop');
    const panel = document.getElementById('forgotPasswordPanel');
    modal.classList.remove('hidden');
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        panel.classList.remove('scale-95', 'opacity-0');
        panel.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeForgotPasswordModal() {
    const modal = document.getElementById('forgotPasswordModal');
    const backdrop = document.getElementById('forgotPasswordBackdrop');
    const panel = document.getElementById('forgotPasswordPanel');
    backdrop.classList.add('opacity-0');
    panel.classList.remove('scale-100', 'opacity-100');
    panel.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        // Reset form
        document.getElementById('forgotPasswordFormContainer').classList.remove('hidden');
        document.getElementById('forgotPasswordSuccess').classList.add('hidden');
        document.getElementById('forgotPasswordEmail').value = '';
        document.getElementById('forgotPasswordError').classList.add('hidden');
    }, 300);
}

async function submitForgotPassword() {
    const email = document.getElementById('forgotPasswordEmail').value;
    const errorEl = document.getElementById('forgotPasswordError');
    const btnText = document.getElementById('forgotPasswordBtnText');
    const spinner = document.getElementById('forgotPasswordSpinner');
    const btn = document.getElementById('forgotPasswordSubmitBtn');

    if (!email || !email.includes('@')) {
        errorEl.textContent = 'Please enter a valid email address.';
        errorEl.classList.remove('hidden');
        return;
    }

    errorEl.classList.add('hidden');
    btnText.classList.add('hidden');
    spinner.classList.remove('hidden');
    btn.disabled = true;

    try {
        const response = await fetch('{{ route('password.email') }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email })
        });

        const data = await response.json().catch(() => ({}));

        if (response.ok) {
            document.getElementById('forgotPasswordFormContainer').classList.add('hidden');
            document.getElementById('forgotPasswordSuccess').classList.remove('hidden');
        } else {
            errorEl.textContent = data.errors?.email?.[0] || data.message || 'Something went wrong. Please try again.';
            errorEl.classList.remove('hidden');
        }
    } catch (e) {
        errorEl.textContent = 'Network error. Please try again.';
        errorEl.classList.remove('hidden');
    } finally {
        btnText.classList.remove('hidden');
        spinner.classList.add('hidden');
        btn.disabled = false;
    }
}

// Close modal on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeForgotPasswordModal();
});
</script>

</body>
</html>
