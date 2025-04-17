<!DOCTYPE html>
{{-- Dodajemy `x-data="{ darkMode: localStorage.getItem('dark') === 'true' }"` i `:class="{'dark': darkMode === true}"` do html --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('dark', val))" :class="{'dark': darkMode === true}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Dodajemy CSRF token --}}

    <title>{{ config('app.name', 'Laravel') }} - Task Manager</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Zmieniamy tło, dodajemy flex i min-h-screen dla przyklejonej stopki --}}
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-800 dark:via-gray-900 dark:to-black text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    {{-- Używamy nowego layoutu nawigacji z Breeze --}}
    @include('layouts.navigation')

    {{-- Dodajemy flex-grow, aby główna treść wypełniała dostępną przestrzeń --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex-grow">
        {{-- Powiadomienia z obsługą dark mode --}}
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-600 text-green-700 dark:text-green-200 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <p class="font-bold">{{ __('app.success') }}</p>
                <p>{{ is_array(session('success')) ? json_encode(session('success')) : session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 dark:border-red-600 text-red-700 dark:text-red-200 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <p class="font-bold">Wystąpiły błędy!</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Główna treść z obsługą dark mode --}}
        <main class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6 md:p-8 mb-8">
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow mb-8 rounded-md p-4">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>
    </div>

    {{-- Stopka z obsługą dark mode i linkiem --}}
    <footer class="text-center text-sm text-gray-500 dark:text-gray-400 py-4 mt-auto">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} Task Manager.
        Made by <a href="https://alwood.ovh" target="_blank" rel="noopener noreferrer" class="underline hover:text-blue-600 dark:hover:text-blue-400">alwood</a>.
    </footer>
</body>
</html>
