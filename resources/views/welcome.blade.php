<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('dark', val))" :class="{'dark': darkMode === true}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Efektywne zarządzanie zadaniami z Task Manager">

        <title>{{ config('app.name', 'Task Manager') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|nunito:400,600,700&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-blue-100 dark:from-gray-800 dark:via-gray-900 dark:to-black text-gray-800 dark:text-gray-200 relative min-h-screen flex flex-col">
        <!-- Hero Section with Navigation -->
        <header class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/30 to-indigo-700/30 dark:from-blue-900/50 dark:to-purple-900/50 z-0"></div>
            
            <nav class="relative z-10 flex items-center justify-between p-6 lg:px-8">
                <div class="flex lg:flex-1">
                    <a href="/" class="-m-1.5 p-1.5 flex items-center">
                        <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-400 dark:to-indigo-300">Task Manager</span>
                    </a>
                </div>

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

                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow transition-all">
                                Moje zadania
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                                Logowanie
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow transition-all">
                                    Zarejestruj się
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>

            <div class="relative z-10 pt-14 pb-20 sm:pb-24 lg:pb-28 px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold tracking-tight text-gray-900 dark:text-white mt-5 mb-8">
                        Zarządzaj zadaniami <span class="text-blue-600 dark:text-blue-400">efektywnie</span>
                    </h1>
                    <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-300">
                        Prosty i intuicyjny menedżer zadań, który pomaga organizować pracę i zwiększać produktywność
                    </p>
                    <div class="mt-10">
                        @auth
                            <a href="{{ route('tasks.index') }}" class="px-8 py-3 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-md transition-all">
                                Przejdź do zadań
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-8 py-3 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-md transition-all">
                                Rozpocznij za darmo
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Features Section -->
        <section class="py-16 bg-white dark:bg-gray-800 shadow-md">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Funkcje, które pokochasz</h2>
                    <p class="mt-4 text-gray-600 dark:text-gray-300">
                        Wszystko czego potrzebujesz do sprawnego zarządzania zadaniami
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-6 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="h-12 w-12 rounded-md bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Proste zarządzanie</h3>
                        <p class="text-gray-600 dark:text-gray-300">Dodawaj, edytuj i zarządzaj zadaniami w prosty i intuicyjny sposób.</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="p-6 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="h-12 w-12 rounded-md bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Śledzenie postępów</h3>
                        <p class="text-gray-600 dark:text-gray-300">Monitoruj swoje postępy i oznaczaj zadania jako ukończone.</p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="p-6 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="h-12 w-12 rounded-md bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tryb ciemny</h3>
                        <p class="text-gray-600 dark:text-gray-300">Korzystaj z aplikacji w trybie ciemnym, aby zmniejszyć zmęczenie oczu.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-800 dark:to-indigo-900">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-6">Gotowy na zwiększenie produktywności?</h2>
                <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                    Dołącz do naszych użytkowników i zacznij zarządzać zadaniami już dziś.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="px-8 py-3 text-base font-medium text-blue-600 bg-white hover:bg-gray-100 rounded-md shadow-md transition-all">
                        Zarejestruj się za darmo
                    </a>
                @else
                    <a href="{{ route('tasks.index') }}" class="px-8 py-3 text-base font-medium text-blue-600 bg-white hover:bg-gray-100 rounded-md shadow-md transition-all">
                        Przejdź do zadań
                    </a>
                @endguest
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} Task Manager.
                        Made by <a href="https://alwood.ovh" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:underline">alwood</a>.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
