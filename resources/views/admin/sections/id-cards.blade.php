<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Cards - {{ $section->name }} | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            min-height: 100vh;
        }
        .id-card-pair {
            break-inside: avoid;
            page-break-inside: avoid;
        }
        @media print {
            .no-print { display: none !important; }
            body { 
                background: white !important;
                backdrop-filter: none !important;
            }
            .modal-panel {
                box-shadow: none !important;
                border: none !important;
                background: white !important;
                max-width: none !important;
                padding: 0 !important;
            }
            .cards-scroll-area {
                max-height: none !important;
                overflow: visible !important;
                background: white !important;
                padding: 0 !important;
            }

            /* Landscape bond paper with small margins */
            @page { size: landscape; margin: 0.25in; }

            /* Override component's single-card print styles for bulk layout */
            body * { visibility: visible !important; }
            .id-card-wrapper {
                position: static !important;
                left: auto !important;
                top: auto !important;
                transform: none !important;
                display: flex !important;
                flex-direction: row !important;
                gap: 0.1in !important;
                align-items: center !important;
                justify-content: center !important;
            }

            /* 2 students per row */
            .bulk-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 0.2in !important;
                align-items: start !important;
                justify-items: center !important;
            }

            .id-card-pair {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
                margin-bottom: 0 !important;
                padding: 0 !important;
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100% !important;
                display: flex !important;
                justify-content: center !important;
            }

            .id-card {
                box-shadow: 0 2px 8px rgba(0,0,0,0.12) !important;
                border: 1px solid #94a3b8 !important;
                page-break-inside: avoid !important;
            }
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="flex items-center justify-center p-4 min-h-screen bg-slate-100" x-data="{ mobileOpen: false }" @keydown.escape.window="history.back()" @click="if ($event.target === $el) history.back()">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen"
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Modal Panel -->
    <div class="modal-panel w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden" @click.stop>
        <!-- Header Toolbar -->
        <div class="no-print bg-white border-b border-slate-200 px-5 py-4 flex items-center justify-between sticky top-0 z-50">
            <div>
                <h1 class="text-lg font-bold text-slate-800">Student ID Cards</h1>
                <p class="text-sm text-slate-500">{{ $section->name }} • {{ $section->gradeLevel->name ?? '' }} • {{ $students->count() }} students</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex h-9 px-4 items-center justify-center rounded-full bg-blue-900 text-white hover:bg-blue-800 transition text-sm font-medium shadow">
                    <i class="fas fa-print mr-2"></i> Print All
                </button>
                <a href="javascript:history.back()" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <i class="fas fa-times text-sm"></i>
                </a>
            </div>
        </div>

        <!-- Cards Content -->
        <div class="cards-scroll-area p-5 max-h-[75vh] overflow-y-auto bg-slate-50">
            @if($students->isEmpty())
                <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-sm no-print">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-id-card text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">No Students Found</h3>
                    <p class="text-slate-500">This section has no enrolled students for the active school year.</p>
                </div>
            @else
                <div class="bulk-grid flex flex-col items-center gap-5">
                    @foreach($students as $student)
                        <div class="id-card-pair bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                            @include('components.student-id-card', ['student' => $student, 'showPrint' => false])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
