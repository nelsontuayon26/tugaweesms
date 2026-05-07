<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrar Dashboard | Student Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-100 to-blue-200 dark:from-slate-900 dark:to-slate-950 p-6">

    <!-- Header -->
    <header class="mb-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo.jpg') }}" class="h-16 w-16 rounded-full shadow" alt="School Logo">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Registrar Dashboard</h1>
                   <p class="text-gray-600 mt-1">
    Welcome, {{ auth()->user()->email }}!
</p>
                </div>
            </div>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition">
                Logout
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </header>

    <!-- Dashboard Cards -->
    <main class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Students -->
        <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-start">
            <h2 class="text-lg font-semibold text-gray-700">Total Students</h2>
            <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalStudents ?? 0 }}</p>

            @if (Route::has('registrar.students.index'))
                <a href="{{ route('registrar.students.index') }}" class="mt-4 text-indigo-600 hover:underline text-sm font-medium">
                    Manage Students
                </a>
            @endif
        </div>

        <!-- Total Teachers -->
        <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-start">
            <h2 class="text-lg font-semibold text-gray-700">Total Teachers</h2>
            <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalTeachers ?? 0 }}</p>

            @if (Route::has('registrar.teachers.index'))
                <a href="{{ route('registrar.teachers.index') }}" class="mt-4 text-indigo-600 hover:underline text-sm font-medium">
                    Manage Teachers
                </a>
            @endif
        </div>

        <!-- Total Sections -->
        <div class="bg-white rounded-2xl shadow p-6 flex flex-col items-start">
            <h2 class="text-lg font-semibold text-gray-700">Total Sections</h2>
            <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalSections ?? 0 }}</p>

            @if (Route::has('registrar.sections.index'))
                <a href="{{ route('registrar.sections.index') }}" class="mt-4 text-indigo-600 hover:underline text-sm font-medium">
                    Manage Sections
                </a>
            @endif
        </div>
    </main>

    <!-- Quick Actions -->
    <section class="max-w-7xl mx-auto mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @if(Route::has('registrar.enrollments.create'))
        <a href="{{ route('registrar.enrollments.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl p-6 shadow transition flex flex-col items-start">
            <h3 class="font-semibold text-lg">Enroll New Student</h3>
            <p class="mt-2 text-sm text-indigo-200">Add a student to a section quickly</p>
        </a>
        @endif

        @if(Route::has('registrar.students.index'))
        <a href="{{ route('registrar.students.index') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-2xl p-6 shadow transition flex flex-col items-start">
            <h3 class="font-semibold text-lg">View Students</h3>
            <p class="mt-2 text-sm text-green-200">Check student records</p>
        </a>
        @endif

        @if(Route::has('registrar.sections.index'))
        <a href="{{ route('registrar.sections.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-2xl p-6 shadow transition flex flex-col items-start">
            <h3 class="font-semibold text-lg">Manage Sections</h3>
            <p class="mt-2 text-sm text-yellow-100">Add or edit class sections</p>
        </a>
        @endif
    </section>

</body>
</html>
