<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book to Inventory | Teacher Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased text-slate-800" x-data="{ mobileOpen: false }">

<!-- Mobile Overlay -->
<div x-show="mobileOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     class="fixed inset-0 z-40 lg:hidden bg-slate-900/30 backdrop-blur-sm"
     style="display: none;"></div>

<!-- Mobile Toggle Button -->
<button @click="mobileOpen = !mobileOpen" 
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
    <i class="fas fa-bars text-lg"></i>
</button>

<div class="flex">
    @include('teacher.includes.sidebar')

    <div class="lg:ml-72 w-full min-h-screen p-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-4">
                <a href="{{ route('teacher.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('teacher.books.inventory') }}" class="hover:text-indigo-600">Inventory</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-indigo-600 font-medium">Add Book</span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <i class="fas fa-plus text-white text-xl"></i>
                        </div>
                        <div>
                            Add Book to Inventory
                            <p class="text-sm font-normal text-slate-500 mt-1">
                                Add new textbooks and learning materials
                            </p>
                        </div>
                    </h1>
                </div>
                <a href="{{ route('teacher.books.inventory') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
                </a>
            </div>
        </div>

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                    <p class="font-semibold text-red-900">Please fix the following errors:</p>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl p-8 max-w-4xl">
            <form method="POST" action="{{ route('teacher.books.storeInventory') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Book Title -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Book Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                               placeholder="Enter book title">
                    </div>

                    <!-- Subject Area -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Subject Area <span class="text-red-500">*</span>
                        </label>
                        <select name="subject_area" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                            <option value="">Select Subject</option>
                            @foreach($subjectAreas as $subject)
                                <option value="{{ $subject }}" {{ old('subject_area') == $subject ? 'selected' : '' }}>
                                    {{ $subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grade Level -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Grade Level <span class="text-red-500">*</span>
                        </label>
                        <select name="grade_level" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                            <option value="">Select Grade</option>
                            @foreach($gradeLevels as $grade)
                                <option value="{{ $grade->name }}" {{ old('grade_level') == $grade->name ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                            <option value="All" {{ old('grade_level') == 'All' ? 'selected' : '' }}>All Grades</option>
                        </select>
                    </div>

                    <!-- Book Code -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Book Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="book_code" value="{{ old('book_code') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                               placeholder="e.g., MATH-6-001">
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            ISBN <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <input type="text" name="isbn" value="{{ old('isbn') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                               placeholder="978-3-16-148410-0">
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Publisher <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <input type="text" name="publisher" value="{{ old('publisher') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                               placeholder="Publisher name">
                    </div>

                    <!-- Publication Year -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Publication Year <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <input type="number" name="publication_year" value="{{ old('publication_year') }}" min="1900" max="{{ date('Y') + 1 }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                               placeholder="{{ date('Y') }}">
                    </div>

                    <!-- Total Copies -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Total Copies <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="total_copies" value="{{ old('total_copies', 1) }}" required min="1" max="1000"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                    </div>

                    <!-- Replacement Cost -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Replacement Cost (₱) <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <input type="number" name="replacement_cost" value="{{ old('replacement_cost') }}" step="0.01" min="0"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500"
                               placeholder="0.00">
                    </div>

                    <!-- Remarks -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Remarks <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <textarea name="remarks" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 resize-none"
                                  placeholder="Additional notes about this book...">{{ old('remarks') }}</textarea>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-8 flex justify-end gap-4">
                    <a href="{{ route('teacher.books.inventory') }}" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-medium rounded-xl shadow-lg shadow-amber-500/30 transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Save Book
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

</body>
</html>