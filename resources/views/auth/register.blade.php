<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-6">

<div class="w-full max-w-lg mx-auto p-4 sm:p-6 bg-white rounded-2xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Student Registration</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        {{-- LRN --}}
        <div class="mb-4">
            <label for="lrn" class="block font-medium text-gray-700">LRN</label>
            <input type="text" name="lrn" id="lrn" value="{{ old('lrn') }}"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('lrn') border-red-500 @enderror">
            @error('lrn')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- First Name --}}
        <div class="mb-4">
            <label for="first_name" class="block font-medium text-gray-700">First Name</label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('first_name') border-red-500 @enderror">
            @error('first_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Middle Name --}}
        <div class="mb-4">
            <label for="middle_name" class="block font-medium text-gray-700">Middle Name</label>
            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('middle_name') border-red-500 @enderror">
            @error('middle_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Last Name --}}
        <div class="mb-4">
            <label for="last_name" class="block font-medium text-gray-700">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('last_name') border-red-500 @enderror">
            @error('last_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Suffix --}}
        <div class="mb-4">
            <label for="suffix" class="block font-medium text-gray-700">Suffix (Optional)</label>
            <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('suffix') border-red-500 @enderror">
            @error('suffix')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Birthday --}}
        <div class="mb-4">
            <label for="birthday" class="block font-medium text-gray-700">Birthday</label>
            <input type="date" name="birthday" id="birthday" value="{{ old('birthday') }}"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('birthday') border-red-500 @enderror">
            @error('birthday')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Username --}}
        <div class="mb-4">
            <label for="username" class="block font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('username') border-red-500 @enderror">
            @error('username')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('email') border-red-500 @enderror">
            @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Password --}}
        <div class="mb-4">
            <label for="password" class="block font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password"
                   class="mt-1 block w-full border rounded-md px-3 py-2 @error('password') border-red-500 @enderror">
            @error('password')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="mt-1 block w-full border rounded-md px-3 py-2">
        </div>

        {{-- Grade Level --}}
        <div class="mb-4">
            <label for="grade_level_id" class="block font-medium text-gray-700">Grade Level</label>
            <select name="grade_level_id" id="grade_level_id"
                    class="mt-1 block w-full border rounded-md px-3 py-2 @error('grade_level_id') border-red-500 @enderror">
                <option value="">Select Grade Level</option>
                @foreach($gradeLevels as $level)
                    <option value="{{ $level->id }}" {{ old('grade_level_id') == $level->id ? 'selected' : '' }}>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
            @error('grade_level_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        {{-- Student Type --}}
        <div class="mb-6">
            <label class="block font-medium text-gray-700">Student Type</label>
            <select name="type" class="mt-1 block w-full border rounded-md px-3 py-2 @error('type') border-red-500 @enderror">
                <option value="new" {{ old('type') == 'new' ? 'selected' : '' }}>New</option>
                <option value="transferee" {{ old('type') == 'transferee' ? 'selected' : '' }}>Transferee</option>
                <option value="continuing" {{ old('type') == 'continuing' ? 'selected' : '' }}>Continuing</option>
            </select>
            @error('type')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition">
            Register
        </button>
    </form>
</div>

</body>
</html>