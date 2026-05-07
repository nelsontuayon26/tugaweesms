<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Principal') - Tugawe Elementary School</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        .principal-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            background: #fafaf9;
            display: flex;
            flex-direction: column;
        }

        .principal-header {
            height: 72px;
            background: rgba(250, 250, 249, 0.9);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid #e7e5e4;
            display: flex;
            align-items: center;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .principal-content {
            flex: 1;
            padding: 28px 32px;
        }

        @media (max-width: 1023px) {
            .principal-wrapper { margin-left: 0 !important; }
            .principal-header { padding: 0 20px; }
            .principal-content { padding: 20px; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-stone-50 text-stone-800 antialiased"
      x-data="{ mobileOpen: false }"
      @keydown.escape.window="mobileOpen = false"
      @toggle-sidebar.window="mobileOpen = !mobileOpen">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/60 backdrop-blur-sm"
         style="display: none;"
         @click="mobileOpen = false">
    </div>

    <!-- Mobile Toggle Button -->
    <button @click="mobileOpen = !mobileOpen"
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-stone-600 hover:text-amber-600 transition-all border border-stone-100">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('principal.includes.sidebar')

        <!-- Main Content -->
        <div class="principal-wrapper flex-1 flex flex-col min-h-screen">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
