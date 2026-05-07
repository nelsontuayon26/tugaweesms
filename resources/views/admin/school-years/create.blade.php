<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create School Year</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Create School Year</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-200 rounded">
                <ul class="list-disc list-inside text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.school-years.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block font-medium mb-1">Name</label>
                <input type="text" name="name" id="name" placeholder="e.g., 2026-2027" 
                       class="border border-gray-300 rounded px-3 py-2 w-full" required>
            </div>

            <div>
                <label for="start_date" class="block font-medium mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" 
                       class="border border-gray-300 rounded px-3 py-2 w-full" required>
            </div>

            <div>
                <label for="end_date" class="block font-medium mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" 
                       class="border border-gray-300 rounded px-3 py-2 w-full" required>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="mr-2">
                <label for="is_active" class="font-medium">Set as active</label>
            </div>

            <div>
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">
                    Create School Year
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.school-years.index') }}" class="text-blue-600 hover:underline">
                Back to School Years
            </a>
        </div>
    </div>

</body>
</html>