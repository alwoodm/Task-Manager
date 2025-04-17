<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            {{-- Fallback styles/scripts if Vite is not running --}}
            <link rel="stylesheet" href="{{ asset('css/app.css') }}">
            <script src="{{ asset('js/app.js') }}" defer></script>
        @endif
        <script>
            // Simple theme toggle logic
            function toggleTheme() {
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            }

            // Apply theme on initial load
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
              document.documentElement.classList.add('dark')
            } else {
              document.documentElement.classList.remove('dark')
            }
        </script>
    </head>
    <body class=\"antialiased bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300\">
        <header class=\"absolute inset-x-0 top-0 z-50 flex items-center justify-between p-6 lg:px-8\">
            {{-- Logo or App Name --}}
            <div class=\"flex lg:flex-1\">
                <a href=\"/\" class=\"-m-1.5 p-1.5 text-lg font-semibold dark:text-white\">
                    Task Manager
                </a>
            </div>

            {{-- Theme Toggle Button --}}
            <button onclick=\"toggleTheme()\" class=\"p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700\">
                <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-6 w-6\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\" stroke-width=\"2\">
                    <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z\" />
                </svg>
                <span class=\"sr-only\">Toggle theme</span>
            </button>

            @if (Route::has('login'))
                <nav class=\"flex flex-1 justify-end items-center space-x-4\">
                    @auth
                        <a
                            href=\"{{ url('/dashboard') }}\"
                            class=\"inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal\"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href=\"{{ route('login') }}\"
                            class=\"inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal\"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href=\"{{ route('register') }}\"
                                class=\"inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal\">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <div class=\"flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0\">
            <div class=\"relative isolate px-6 pt-14 lg:px-8\">
                {{-- ... existing content ... --}}
            </div>
        </div>
    </body>
</html>
