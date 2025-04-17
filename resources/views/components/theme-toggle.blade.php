<div 
    {{ $attributes->merge(['class' => 'fixed bottom-6 right-6 z-50']) }}
    x-data="{ hover: false }" 
    @mouseenter="hover = true" 
    @mouseleave="hover = false"
>
    <button 
        type="button"
        @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode)" 
        :class="{'scale-110': hover, 'rotate-180': darkMode && hover}"
        class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 dark:bg-indigo-600 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform"
        aria-label="Toggle dark mode"
    >
        <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>
