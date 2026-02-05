<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Welcome back')" :description="__('Enter your email and password to access your workspace.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="you@company.com"
                class="bg-white/50 dark:bg-slate-800/50"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Enter your password')"
                    viewable
                    class="bg-white/50 dark:bg-slate-800/50"
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 right-0 text-xs font-bold text-[#4facfe] hover:text-[#4facfe]/80" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Keep me logged in')" :checked="old('remember')" />

            <div class="pt-2">
                <button type="submit" class="w-full bg-[#4facfe] text-white font-bold text-lg px-8 py-3.5 rounded-full shadow-lg shadow-[#4facfe]/30 hover:-translate-y-0.5 hover:shadow-[#4facfe]/40 transition-all active:scale-95" data-test="login-button">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="text-center text-sm font-medium text-slate-500 dark:text-slate-400">
                <span>{{ __('New to Atomic Flow?') }}</span>
                <a href="{{ route('register') }}" class="text-[#4facfe] hover:text-[#4facfe]/80 font-bold ml-1" wire:navigate>{{ __('Create account') }}</a>
            </div>
        @endif
    </div>
</x-layouts.auth>
