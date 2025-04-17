<nav class="absolute top-0 left-0 w-full z-10 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="flex items-center">
                    <x-application-logo class="block h-10 w-auto fill-current text-white" />
                    <span class="ml-3 text-2xl font-bold text-white">Task Manager</span>
                </a>
            </div>
            
            <div class="hidden sm:flex items-center space-x-4">
                @auth
                    <a href="{{ route('tasks.index') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Log in
                    </a>
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md text-sm font-medium shadow transition-colors">
                        Register
                    </a>
                @endauth

                <!-- Theme toggle button -->
                <button
                    @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode)"
                    class="p-2 rounded-full text-white hover:bg-white/10 transition-colors"
                    aria-label="Toggle dark mode"
                >
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
            
            <!-- Mobile menu button -->
            <div class="flex sm:hidden">
                <button
                    type="button"
                    @click="mobileMenu = !mobileMenu"
                    class="p-2 rounded-md text-white hover:bg-white/10"
                    x-data="{ mobileMenu: false }"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                >
                    <svg class="h-6 w-6" x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" x-show="mobileMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div class="sm:hidden" x-data="{ mobileMenu: false }" x-show="mobileMenu" x-transition>
        <div class="px-2 pt-2 pb-3 space-y-1 bg-white/10 backdrop-blur-lg">
            @auth
                <a href="{{ route('tasks.index') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">
                    Register
                </a>
            @endauth
            
            <button
                @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode)"
                class="w-full text-left text-white px-3 py-2 rounded-md text-base font-medium"
            >
                <span x-show="!darkMode">Light mode</span>
                <span x-show="darkMode">Dark mode</span>
            </button>
        </div>
    </div>
</nav>
