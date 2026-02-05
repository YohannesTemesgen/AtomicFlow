<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100"
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
                class="flex-shrink-0 bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 flex flex-col transition-all duration-300 ease-in-out h-full overflow-y-auto hidden lg:flex z-30">
                
                <div 
                    :class="isSidebarCollapsed ? 'px-4 justify-center' : 'px-6'"
                    class="h-16 flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 transition-all duration-300 overflow-hidden">
                    <a href="{{ route('dashboard') }}" class="flex items-center min-w-0" wire:navigate>
                        <x-app-logo-icon class="size-8 shrink-0 text-zinc-900 dark:text-white" />
                        <span 
                            x-show="!isSidebarCollapsed" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            class="ml-2 text-lg font-bold text-zinc-900 dark:text-white truncate">AtomicFlow</span>
                    </a>
                    <button 
                        @click="toggleSidebar()" 
                        class="p-1.5 rounded-lg text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors duration-200 hidden lg:block shrink-0"
                        :aria-label="isSidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                        <span class="material-symbols-outlined text-xl transition-transform duration-300" :class="isSidebarCollapsed ? 'rotate-180' : ''">menu_open</span>
                    </button>
                </div>

                <nav class="flex-1 px-4 py-6 space-y-6">
                    <flux:navlist variant="outline">
                        <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                            <span x-show="!isSidebarCollapsed">{{ __('Dashboard') }}</span>
                        </flux:navlist.item>
                        <flux:navlist.item icon="view-columns" :href="route('kanban.board')" :current="request()->routeIs('kanban.board')" wire:navigate>
                            <span x-show="!isSidebarCollapsed">{{ __('Kanban Board') }}</span>
                        </flux:navlist.item>
                        <flux:navlist.item icon="cog-6-tooth" :href="route('app.settings')" :current="request()->routeIs('app.settings')" wire:navigate>
                            <span x-show="!isSidebarCollapsed">{{ __('Settings') }}</span>
                        </flux:navlist.item>
                    </flux:navlist>

                    <flux:spacer />

                    <div class="px-2 space-y-4">
                        <button onclick="themeManager.toggle()" class="w-full flex items-center gap-2 p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-500 dark:text-zinc-400 transition-colors focus:outline-hidden" title="Toggle Theme">
                            <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                            <span class="material-symbols-outlined hidden dark:block">light_mode</span>
                            <span x-show="!isSidebarCollapsed" class="text-sm font-medium">{{ __('Toggle Theme') }}</span>
                        </button>

                        @if(auth()->check())
                        <flux:dropdown position="bottom" align="start" class="w-full">
                            <flux:profile
                                :initials="auth()->user()->initials()"
                                :name="!isSidebarCollapsed ? auth()->user()->name : ''"
                                icon-trailing="chevron-down"
                                class="w-full cursor-pointer"
                            />

                            <flux:menu class="w-[220px]">
                                <flux:menu.item :href="route('admin.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                                <flux:menu.separator />
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                        {{ __('Log Out') }}
                                    </flux:menu.item>
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                        @endif
                    </div>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden bg-white dark:bg-zinc-950">
                <!-- Mobile Header -->
                <header class="lg:hidden h-16 bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between px-4 z-20">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-app-logo-icon class="size-8 text-zinc-900 dark:text-white" />
                    </a>
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="p-2 text-zinc-500">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                </header>

                <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 transition-all duration-300 ease-in-out">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="isMobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 lg:hidden" @click="isMobileMenuOpen = false" x-cloak>
            <div class="absolute inset-0 bg-zinc-900/50 backdrop-blur-sm"></div>
        </div>

        <!-- Mobile Menu Content -->
        <div x-show="isMobileMenuOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-zinc-900 z-50 lg:hidden shadow-xl" x-cloak>
            <div class="h-16 flex items-center justify-between px-6 border-b border-zinc-200 dark:border-zinc-800">
                <a href="{{ route('dashboard') }}" class="flex items-center" wire:navigate>
                    <x-app-logo-icon class="size-8 text-zinc-900 dark:text-white" />
                    <span class="ml-2 text-lg font-bold text-zinc-900 dark:text-white">AtomicFlow</span>
                </a>
                <button @click="isMobileMenuOpen = false" class="text-zinc-500">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <nav class="px-4 py-6">
                <flux:navlist variant="outline">
                    <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="view-columns" :href="route('kanban.board')" :current="request()->routeIs('kanban.board')" wire:navigate>
                        {{ __('Kanban Board') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="cog-6-tooth" :href="route('app.settings')" :current="request()->routeIs('app.settings')" wire:navigate>
                        {{ __('Settings') }}
                    </flux:navlist.item>
                </flux:navlist>
            </nav>
        </div>

        {{ $slot }}

        @stack('scripts')
        @fluxScripts
    </body>
</html>
