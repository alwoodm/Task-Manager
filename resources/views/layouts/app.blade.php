<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('dark', val))" :class="{'dark': darkMode === true}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Task Manager') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    @include('layouts.navigation')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex-grow mt-6">
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-600 text-green-700 dark:text-green-200 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ is_array(session('success')) ? json_encode(session('success')) : session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 dark:border-red-600 text-red-700 dark:text-red-200 p-4 mb-6 rounded-md shadow-sm" role="alert">
                <p class="font-bold">Errors occurred!</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

    <!-- Theme Toggle Button -->
    <x-theme-toggle />

    <footer class="text-center text-sm text-gray-500 dark:text-gray-400 py-4 mt-auto">
        &copy; {{ date('Y') }} Task Manager.
        Made by <a href="#" class="underline hover:text-blue-600 dark:hover:text-blue-400">alwood</a>.
    </footer>
</body>
</html>
