<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.theme-script')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard' }} - {{ config('app.name') }}</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "{{ auth()->user()?->tenant?->theme_color ?? '#10B981' }}",
                        secondary: "#10B981",
                        "background-light": "#F3F4F6",
                        "background-dark": "#111827",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1F2937",
                        "text-light": "#1F2937",
                        "text-dark": "#F9FAFB",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                        body: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        .note-editor { border: 1px solid #e5e7eb !important; border-radius: 0.5rem !important; }
        .dark .note-editor { border-color: #374151 !important; background: #1f2937 !important; }
        .dark .note-editing-area { background: #1f2937 !important; }
        .dark .note-editable { background: #1f2937 !important; color: #f9fafb !important; }
        .dark .note-toolbar { background: #374151 !important; border-color: #4b5563 !important; }
        .dark .note-btn { background: #4b5563 !important; border-color: #6b7280 !important; color: #f9fafb !important; }
    </style>
    
    @livewireStyles
</head>
<body class="font-body bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark transition-colors duration-200"
    x-data="{ 
        isSidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', 
        isMobileMenuOpen: false,
        toggleSidebar() {
            this.isSidebarCollapsed = !this.isSidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.isSidebarCollapsed);
        }
    }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside 
            id="sidebar"
            :class="isSidebarCollapsed ? 'w-20' : 'w-64'"
            :aria-expanded="!isSidebarCollapsed"
            class="flex-shrink-0 bg-surface-light dark:bg-surface-dark border-r border-gray-200 dark:border-gray-700 flex flex-col transition-all duration-300 ease-in-out h-full overflow-y-auto hidden md:flex z-30">
            <div 
                :class="isSidebarCollapsed ? 'px-4 justify-center' : 'px-6'"
                class="h-16 flex items-center justify-between border-b border-gray-100 dark:border-gray-800 transition-all duration-300 overflow-hidden">
                <div class="flex items-center min-w-0">
                    <span class="material-icons-outlined text-primary shrink-0" :class="isSidebarCollapsed ? 'mr-0' : 'mr-2'">bolt</span>
                    <span 
                        x-show="!isSidebarCollapsed" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="text-xl font-bold text-gray-800 dark:text-white truncate">{{ auth()->user()?->tenant?->name ?? 'Dashboard' }}</span>
                </div>
                <button 
                    @click="toggleSidebar()" 
                    class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 hidden md:block shrink-0"
                    :aria-label="isSidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                    <span class="material-icons-outlined text-sm transition-transform duration-300" :class="isSidebarCollapsed ? 'rotate-180' : ''">menu_open</span>
                </button>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-6">
                <div>
                    <h3 
                        x-show="!isSidebarCollapsed"
                        class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Main Menu</h3>
                    <ul class="space-y-1" role="menu">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" 
                                :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-2'"
                                class="flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }} group transition-all duration-300"
                                role="menuitem"
                                :title="isSidebarCollapsed ? 'Dashboard' : ''">
                                <span class="material-icons-outlined text-lg shrink-0" :class="isSidebarCollapsed ? '' : 'mr-3'">dashboard</span>
                                <span x-show="!isSidebarCollapsed" class="truncate">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.boards') }}" 
                                :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-2'"
                                class="flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.boards*') ? 'bg-primary text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }} group transition-all duration-300"
                                role="menuitem"
                                :title="isSidebarCollapsed ? 'Boards' : ''">
                                <span class="material-icons-outlined text-lg shrink-0" :class="isSidebarCollapsed ? '' : 'mr-3'">view_kanban</span>
                                <span x-show="!isSidebarCollapsed" class="truncate">Boards</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 
                        x-show="!isSidebarCollapsed"
                        class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Configuration</h3>
                    <ul class="space-y-1" role="menu">
                        <li>
                            <a href="{{ route('admin.board-types') }}" 
                                :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-2'"
                                class="flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.board-types*') ? 'bg-primary text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }} group transition-all duration-300"
                                role="menuitem"
                                :title="isSidebarCollapsed ? 'Board Types' : ''">
                                <span class="material-icons-outlined text-lg shrink-0" :class="isSidebarCollapsed ? '' : 'mr-3'">category</span>
                                <span x-show="!isSidebarCollapsed" class="truncate">Board Types</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings') }}" 
                                :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-2'"
                                class="flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings') ? 'bg-primary text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }} group transition-all duration-300"
                                role="menuitem"
                                :title="isSidebarCollapsed ? 'Settings' : ''">
                                <span class="material-icons-outlined text-lg shrink-0" :class="isSidebarCollapsed ? '' : 'mr-3'">settings</span>
                                <span x-show="!isSidebarCollapsed" class="truncate">Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 
                        x-show="!isSidebarCollapsed"
                        class="px-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">User</h3>
                    <ul class="space-y-1" role="menu">
                        <li>
                            <a href="{{ route('admin.profile') }}" 
                                :class="isSidebarCollapsed ? 'justify-center px-0' : 'px-2'"
                                class="flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.profile') ? 'bg-primary text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }} group transition-all duration-300"
                                role="menuitem"
                                :title="isSidebarCollapsed ? 'Profile' : ''">
                                <span class="material-icons-outlined text-lg shrink-0" :class="isSidebarCollapsed ? '' : 'mr-3'">person</span>
                                <span x-show="!isSidebarCollapsed" class="truncate">Profile</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden bg-background-light dark:bg-background-dark">
            <!-- Header -->
            <header class="h-16 bg-surface-light dark:bg-surface-dark border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6 z-10 transition-colors duration-200">
                <div class="flex items-center">
                    <button 
                        @click="window.innerWidth < 768 ? isMobileMenuOpen = true : toggleSidebar()"
                        class="p-2 rounded-lg text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-200 mr-4"
                        aria-label="Toggle Sidebar">
                        <span class="material-icons-outlined transition-transform duration-300" 
                            :class="{'rotate-180': isSidebarCollapsed && window.innerWidth >= 768}"
                            x-text="isSidebarCollapsed && window.innerWidth >= 768 ? 'menu_open' : 'menu'"></span>
                    </button>
                    
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white truncate">{{ $header ?? 'Dashboard' }}</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button onclick="themeManager.toggle()" class="p-2 rounded-full text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-white focus:outline-none bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700" id="theme-toggle">
                        <span class="material-icons-outlined text-xl dark:hidden">dark_mode</span>
                        <span class="material-icons-outlined text-xl hidden dark:block">light_mode</span>
                    </button>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center max-w-xs rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-1 pr-3">
                            <span class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-semibold text-sm mr-2">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                            </span>
                            <div class="text-left hidden lg:block">
                                <p class="text-sm font-medium text-gray-700 dark:text-white">{{ auth()->user()?->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth()->user()?->role ?? 'Admin') }}</p>
                            </div>
                            <span class="material-icons-outlined text-gray-400 ml-2 text-sm">expand_more</span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-background-dark p-6 transition-colors duration-200">
                <div class="max-w-7xl mx-auto">
                    @if (session()->has('message'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg">
                            {{ session('message') }}
                        </div>
                    @endif
                    
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="isMobileMenuOpen" class="fixed inset-0 z-50 md:hidden" x-cloak>
        <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="isMobileMenuOpen = false"></div>
        <div class="fixed inset-y-0 left-0 flex flex-col w-64 bg-surface-light dark:bg-surface-dark shadow-xl"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full">
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center">
                    <span class="material-icons-outlined text-primary mr-2">bolt</span>
                    <span class="text-xl font-bold text-gray-800 dark:text-white">{{ auth()->user()?->tenant?->name ?? 'Dashboard' }}</span>
                </div>
                <button @click="isMobileMenuOpen = false" class="text-gray-500">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
                <ul class="space-y-1">
                    <li><a href="{{ route('admin.dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span class="material-icons-outlined mr-3">dashboard</span>Dashboard</a></li>
                    <li><a href="{{ route('admin.boards') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span class="material-icons-outlined mr-3">view_kanban</span>Boards</a></li>
                    <li><a href="{{ route('admin.board-types') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span class="material-icons-outlined mr-3">category</span>Board Types</a></li>
                    <li><a href="{{ route('admin.settings') }}" class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><span class="material-icons-outlined mr-3">settings</span>Settings</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <script>
        // Listen for theme changes to sync UI if needed
        window.addEventListener('theme-changed', (e) => {
            // Any specific admin UI syncing can go here
        });
    </script>
    
    @livewireScripts
</body>
</html>
