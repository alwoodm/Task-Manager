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

    {{-- Dodajemy odstęp (mt-6) między navbarem a główną treścią --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex-grow mt-6">
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

    {{-- Floating Dark Mode Toggle Button --}}
    <div 
        x-data="{ hover: false }" 
        @mouseenter="hover = true" 
        @mouseleave="hover = false"
        class="fixed bottom-6 right-6 z-50"
    >
        <button 
            @click="darkMode = !darkMode" 
            :class="{'scale-110': hover, 'rotate-180': darkMode && hover}"
            class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 dark:bg-indigo-600 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform"
        >
            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </div>

    {{-- Stopka z obsługą dark mode i linkiem --}}
    <footer class="text-center text-sm text-gray-500 dark:text-gray-400 py-4 mt-auto">
        &copy; {{ date('Y') }} Task Manager.
        Made by <a href="https://alwood.ovh" target="_blank" rel="noopener noreferrer" class="underline hover:text-blue-600 dark:hover:text-blue-400">alwood</a>.
    </footer>
</body>
</html>
