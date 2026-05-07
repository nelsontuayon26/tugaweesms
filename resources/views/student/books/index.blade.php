<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books - Tugawe Elementary School</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased"
      x-data="{ 
          sidebarCollapsed: false, 
          mobileOpen: false,
          init() {
              if (window.innerWidth >= 1024) {
                  this.sidebarCollapsed = false;
              } else {
                  this.mobileOpen = false;
              }
          }
      }"
      x-init="init()"
      @resize.window="
          if (window.innerWidth < 1024) {
              sidebarCollapsed = false;
          }
      "
      @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
         style="display: none;"></div>

    <!-- Mobile Toggle Button -->
    <button @click="mobileOpen = !mobileOpen"
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg shadow-slate-200/50 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:scale-105 hover:shadow-xl transition-all duration-200 border border-slate-100">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <!-- Sidebar -->
    @include('student.includes.sidebar')

    <!-- Main Content -->
    <main class="min-h-screen transition-all duration-300 ease-out lg:ml-72">

        <div class="p-4 md:p-8">

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900">My Books</h1>
                <p class="text-slate-500 text-sm mt-1">
                    Books issued and returned
                    @if($activeSchoolYear)
                        <span class="text-slate-400">&middot; S.Y. {{ $activeSchoolYear->name }}</span>
                    @endif
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $borrowedBooks->count() }}</p>
                            <p class="text-xs text-slate-500">Currently Borrowed</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $returnedBooks->count() }}</p>
                            <p class="text-xs text-slate-500">Returned</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $damagedBooks->count() }}</p>
                            <p class="text-xs text-slate-500">Damaged</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $lostBooks->count() }}</p>
                            <p class="text-xs text-slate-500">Lost</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books List -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-800">Book Records</h2>
                    <span class="text-xs text-slate-400">{{ $allBooks->count() }} total</span>
                </div>

                @if($allBooks->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-slate-300 text-2xl"></i>
                        </div>
                        <h3 class="text-slate-700 font-medium mb-1">No books found</h3>
                        <p class="text-slate-400 text-sm">You don't have any book records for this school year.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="text-left px-5 py-3 font-medium text-slate-500">Book Title</th>
                                    <th class="text-left px-5 py-3 font-medium text-slate-500">Subject</th>
                                    <th class="text-left px-5 py-3 font-medium text-slate-500">Copy #</th>
                                    <th class="text-left px-5 py-3 font-medium text-slate-500">Book Code</th>
                                    <th class="text-left px-5 py-3 font-medium text-slate-500">Date Issued</th>
                                    <th class="text-left px-5 py-3 font-medium text-slate-500">Date Returned</th>
                                    <th class="text-left px-5 py-3 font-medium text-slate-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($allBooks as $book)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                                <span class="font-medium text-slate-800">{{ $book->title }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-slate-600">{{ $book->subject_area ?? '—' }}</td>
                                        <td class="px-5 py-3">
                                            @if($book->copy_number)
                                                <span class="inline-flex items-center px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-md">Copy #{{ $book->copy_number }}</span>
                                            @else
                                                <span class="text-slate-400 text-xs">—</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $book->book_code ?? '—' }}</td>
                                        <td class="px-5 py-3 text-slate-600">
                                            {{ $book->date_issued ? $book->date_issued->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="px-5 py-3 text-slate-600">
                                            {{ $book->date_returned ? $book->date_returned->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="px-5 py-3">
                                            @if($book->status === 'issued' && is_null($book->date_returned))
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>Borrowed
                                                </span>
                                            @elseif($book->status === 'returned')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                                    <i class="fas fa-check mr-1 text-[10px]"></i>Returned
                                                </span>
                                            @elseif($book->status === 'damaged')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                                    <i class="fas fa-exclamation mr-1 text-[10px]"></i>Damaged
                                                </span>
                                            @elseif($book->status === 'lost')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                                    <i class="fas fa-times mr-1 text-[10px]"></i>Lost
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                                    {{ ucfirst($book->status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
        </main>

</body>
</html>
