<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        {{-- Landing Page Fonts --}}
        <link href="https://fonts.googleapis.com" rel="preconnect"/>
        <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Inter:wght@400;500&amp;display=swap" rel="stylesheet"/>
        
        <style>
            /* Landing Page Styles */
            .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
            .font-sans { font-family: 'Inter', sans-serif; }
            
            .blob-bg {
                background-image: radial-gradient(circle at 20% 30%, rgba(79, 172, 254, 0.15) 0%, transparent 50%),
                                  radial-gradient(circle at 80% 70%, rgba(255, 154, 158, 0.15) 0%, transparent 50%);
                background-attachment: fixed;
            }
            
            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.5);
            }
            
            .dark .glass-card {
                background: rgba(15, 23, 42, 0.7);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }

            /* Custom Colors based on Landing Page */
            .text-primary { color: #4facfe; }
            .bg-primary { background-color: #4facfe; }
            .hover\:bg-primary:hover { background-color: #4facfe; }
            
            .text-secondary { color: #ff9a9e; }
            
            /* Flux Overrides for consistency */
            [data-flux-control] {
                border-radius: 0.75rem !important; /* rounded-xl */
                border-color: #e2e8f0 !important;
            }
            .dark [data-flux-control] {
                border-color: #334155 !important;
                background-color: #1e293b !important;
            }
            
            [data-flux-button] {
                border-radius: 9999px !important; /* rounded-full */
                font-weight: 700 !important;
            }
        </style>
    </head>
    <body class="min-h-screen font-sans antialiased blob-bg bg-white dark:bg-[#0f172a] text-slate-700 dark:text-slate-300 flex flex-col items-center justify-center p-6">
        
        {{-- Home Link --}}
        <div class="absolute top-6 left-6 md:top-10 md:left-10 z-50">
             <a href="{{ route('home') }}" class="flex items-center gap-3 group" wire:navigate>
                <div class="size-10 bg-[#4facfe] rounded-xl flex items-center justify-center text-white shadow-lg shadow-[#4facfe]/30 transition-transform group-hover:scale-105">
                    <span class="material-symbols-outlined !text-2xl">bubble_chart</span>
                </div>
                <h2 class="text-xl font-extrabold tracking-tight font-display text-slate-900 dark:text-white hidden md:block">Atomic <span class="text-[#4facfe]">Flow</span></h2>
            </a>
        </div>

        {{-- Theme Toggle --}}
        <div class="absolute top-6 right-6 md:top-10 md:right-10 z-50">
            <button onclick="themeManager.toggle()" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors focus:outline-hidden" title="Toggle Theme">
                <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                <span class="material-symbols-outlined hidden dark:block">light_mode</span>
            </button>
        </div>

        {{-- Main Card --}}
        <div 
            x-data 
            x-init="$el.classList.remove('opacity-0', 'translate-y-4'); $el.classList.add('opacity-100', 'translate-y-0')"
            class="w-full max-w-[480px] glass-card rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-[#4facfe]/10 opacity-0 translate-y-4 transition-all duration-700 ease-out"
        >
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <div class="mt-8 text-center text-sm font-medium text-slate-500 dark:text-slate-500">
            &copy; {{ date('Y') }} Atomic Flow. Work happier.
        </div>

        @fluxScripts
    </body>
</html>
