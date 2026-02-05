<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.theme-script')
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes"/>
    <meta name="theme-color" content="#3B82F6"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-title" content="Tracker"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="description" content="Lead Conversion Pipeline - Track and manage your sales leads efficiently"/>
    
    <title>{{ $title ?? 'Lead Conversion Pipeline' }}</title>
    
    <link rel="manifest" href="/manifest.json"/>
    <link rel="icon" href="/favicon.ico" sizes="any"/>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml"/>
    <link rel="apple-touch-icon" href="/apple-touch-icon.png"/>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3B82F6",
                        "background-light": "#F9FAFB",
                        "background-dark": "#111827",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
        
        /* Mobile-first touch improvements */
        @media (max-width: 768px) {
            .touch-target { min-height: 44px; min-width: 44px; }
            .mobile-scroll { -webkit-overflow-scrolling: touch; }
        }
        
        /* Safe area insets for notched devices */
        @supports (padding: env(safe-area-inset-bottom)) {
            .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
            .safe-top { padding-top: env(safe-area-inset-top); }
        }
        
        /* Smooth transitions */
        .slide-enter { transform: translateX(100%); }
        .slide-enter-active { transform: translateX(0); transition: transform 0.3s ease-out; }
        .slide-exit { transform: translateX(0); }
        .slide-exit-active { transform: translateX(100%); transition: transform 0.3s ease-in; }
        
        /* Bottom nav active state */
        .bottom-nav-active { color: #3B82F6; }
        .bottom-nav-active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: #3B82F6;
            border-radius: 0 0 4px 4px;
        }
        
        /* PWA install banner */
        .pwa-install-banner {
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Pull to refresh indicator */
        .pull-indicator {
            transition: transform 0.2s ease;
        }
    </style>
    @livewireStyles
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div class="flex flex-col lg:flex-row min-h-screen min-h-[100dvh]">
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex flex-col w-64 shrink-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 fixed inset-y-0 z-50">
        <div class="h-16 flex items-center px-6 border-b border-gray-200 dark:border-gray-700">
            <a class="flex items-center space-x-2" href="{{ route('home') }}">
                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <span class="font-bold text-lg text-gray-800 dark:text-white">Tracker</span>
            </a>
        </div>
        
        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="{{ route('dashboard') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('kanban.board') ? 'bg-primary/10 text-primary' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="{{ route('kanban.board') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                Lead Pipeline
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('task.tracker*') ? 'bg-purple-500/10 text-purple-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="{{ route('task.tracker') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tasks
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('app.settings') ? 'bg-primary/10 text-primary' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="{{ route('app.settings') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col lg:pl-64 transition-all duration-300">
        <!-- Header - Desktop & Mobile -->
        <header class="bg-white dark:bg-gray-800/95 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 w-full shrink-0 sticky top-0 z-40 safe-top">
            <div class="container mx-auto px-4 sm:px-6 py-3">
                <div class="flex items-center justify-between">
                    <!-- Logo & Desktop Nav -->
                    <div class="flex items-center space-x-4 lg:hidden">
                        <a class="flex items-center space-x-2" href="{{ route('home') }}">
                            <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 0 0 2.25-2.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v2.25A2.25 2.25 0 0 0 6 10.5Zm0 9.75h2.25A2.25 2.25 0 0 0 10.5 18v-2.25a2.25 2.25 0 0 0-2.25-2.25H6a2.25 2.25 0 0 0-2.25 2.25V18A2.25 2.25 0 0 0 6 20.25Zm9.75-9.75H18a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 18 3.75h-2.25A2.25 2.25 0 0 0 13.5 6v2.25a2.25 2.25 0 0 0 2.25 2.25Z" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="font-bold text-lg text-gray-800 dark:text-white hidden sm:inline">Tracker</span>
                        </a>
                    </div>
                    
                    <!-- Spacer -->
                    <div class="hidden lg:block lg:flex-1"></div>

                    <!-- Desktop Auth Actions -->
                    <div class="hidden lg:flex items-center space-x-3">
                        <button onclick="themeManager.toggle()" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all mr-2" title="Toggle Theme">
                            <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                            <span class="material-symbols-outlined hidden dark:block">light_mode</span>
                        </button>
                        @auth
                            <a class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all" href="{{ route('admin.profile') }}">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition-all active:scale-95">Logout</button>
                            </form>
                        @else
                            <a class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all" href="{{ route('login') }}">Log in</a>
                            <a class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition-all active:scale-95" href="{{ route('register') }}">Register</a>
                        @endauth
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all touch-target" aria-label="Open menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

    <!-- Mobile Slide-out Menu -->
    <div id="mobile-menu" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="mobile-menu-overlay"></div>
        <div class="absolute right-0 top-0 bottom-0 w-80 max-w-[85vw] bg-white dark:bg-gray-800 shadow-2xl transform translate-x-full transition-transform duration-300" id="mobile-menu-panel">
            <div class="flex flex-col h-full">
                <!-- Menu Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <span class="font-bold text-lg text-gray-800 dark:text-white">Menu</span>
                    <button id="mobile-menu-close" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all touch-target">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Menu Items -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-2 mobile-scroll">
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="{{ route('dashboard') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-all {{ request()->routeIs('kanban.board') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="{{ route('kanban.board') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                        </svg>
                        Lead Pipeline
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-all {{ request()->routeIs('task.tracker*') ? 'bg-purple-500/10 text-purple-600' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="{{ route('task.tracker') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Task Tracker
                    </a>
                    <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium transition-all {{ request()->routeIs('app.settings') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}" href="{{ route('app.settings') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </a>
                </nav>
                
                <!-- Menu Footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-3 safe-bottom">
                    @auth
                        <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all" href="{{ route('admin.profile') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary text-white px-4 py-3 rounded-xl text-base font-semibold hover:bg-blue-600 transition-all active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    @else
                        <a class="block w-full text-center px-4 py-3 rounded-xl text-base font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all" href="{{ route('login') }}">Log in</a>
                        <a class="block w-full text-center bg-primary text-white px-4 py-3 rounded-xl text-base font-semibold hover:bg-blue-600 transition-all" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 overflow-x-auto p-4 sm:p-6 pb-20 lg:pb-6">
        <div class="container mx-auto">
            {{ $slot }}
        </div>
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-30 safe-bottom">
        <div class="flex items-center justify-around py-2">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 px-3 py-2 relative {{ request()->routeIs('dashboard') ? 'bottom-nav-active' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>
            <a href="{{ route('kanban.board') }}" class="flex flex-col items-center gap-1 px-3 py-2 relative {{ request()->routeIs('kanban.board') ? 'bottom-nav-active' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                </svg>
                <span class="text-xs font-medium">Leads</span>
            </a>
            <a href="{{ route('task.tracker') }}" class="flex flex-col items-center gap-1 px-3 py-2 relative {{ request()->routeIs('task.tracker*') ? 'text-purple-600' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs font-medium">Tasks</span>
            </a>
            <a href="{{ route('app.settings') }}" class="flex flex-col items-center gap-1 px-3 py-2 relative {{ request()->routeIs('app.settings') ? 'bottom-nav-active' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-xs font-medium">Settings</span>
            </a>
        </div>
    </nav>

    <!-- PWA Install Banner (hidden by default) -->
    <div id="pwa-install-banner" class="hidden fixed bottom-20 lg:bottom-4 left-4 right-4 lg:left-auto lg:right-4 lg:w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 z-50 pwa-install-banner">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 dark:text-white">Install Tracker</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add to your home screen for quick access</p>
            </div>
            <button id="pwa-install-close" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="flex gap-3 mt-4">
            <button id="pwa-install-btn" class="flex-1 bg-primary text-white py-2.5 rounded-xl font-semibold hover:bg-blue-600 transition-all active:scale-[0.98]">Install</button>
            <button id="pwa-install-later" class="px-4 py-2.5 rounded-xl font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">Later</button>
        </div>
    </div>
    </div>
</div>

@livewireScripts
@stack('scripts')

<script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuPanel = document.getElementById('mobile-menu-panel');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

    function openMobileMenu() {
        mobileMenu.classList.remove('hidden');
        setTimeout(() => {
            mobileMenuPanel.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenuPanel.classList.add('translate-x-full');
        setTimeout(() => {
            mobileMenu.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    mobileMenuBtn?.addEventListener('click', openMobileMenu);
    mobileMenuClose?.addEventListener('click', closeMobileMenu);
    mobileMenuOverlay?.addEventListener('click', closeMobileMenu);

    // Service Worker Registration
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('SW registered:', registration.scope);
                })
                .catch(error => {
                    console.log('SW registration failed:', error);
                });
        });
    }

    // PWA Install Prompt
    let deferredPrompt;
    const pwaInstallBanner = document.getElementById('pwa-install-banner');
    const pwaInstallBtn = document.getElementById('pwa-install-btn');
    const pwaInstallClose = document.getElementById('pwa-install-close');
    const pwaInstallLater = document.getElementById('pwa-install-later');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Show install banner after 30 seconds if not dismissed
        const dismissed = localStorage.getItem('pwa-install-dismissed');
        if (!dismissed) {
            setTimeout(() => {
                pwaInstallBanner?.classList.remove('hidden');
            }, 30000);
        }
    });

    pwaInstallBtn?.addEventListener('click', async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log('PWA install outcome:', outcome);
            deferredPrompt = null;
            pwaInstallBanner?.classList.add('hidden');
        }
    });

    pwaInstallClose?.addEventListener('click', () => {
        pwaInstallBanner?.classList.add('hidden');
        localStorage.setItem('pwa-install-dismissed', 'true');
    });

    pwaInstallLater?.addEventListener('click', () => {
        pwaInstallBanner?.classList.add('hidden');
    });

    // Online/Offline status
    window.addEventListener('online', () => {
        document.body.classList.remove('offline');
    });

    window.addEventListener('offline', () => {
        document.body.classList.add('offline');
    });
</script>
</body>
</html>
