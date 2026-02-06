<x-layouts.auth-split>
    <div class="lg:hidden flex items-center gap-2 mb-4">
        <div class="size-10 bg-primary rounded-xl flex items-center justify-center text-white">
            <span class="material-symbols-outlined !text-2xl">folder_managed</span>
        </div>
        <h2 class="text-2xl font-extrabold tracking-tight font-display text-slate-800">Atomic <span class="text-primary">Flow</span></h2>
    </div>

    <div class="flex flex-col gap-2 mb-6">
        <h2 class="text-3xl font-extrabold font-display text-slate-900">{{ __('Welcome back') }}</h2>
        <p class="text-slate-500 font-medium">{{ __('Enter your email and password to access your workspace.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
        @csrf
        
        <!-- Email -->
        <div class="flex flex-col gap-2">
            <label class="text-sm font-bold text-slate-700" for="email">{{ __('Email') }}</label>
            <input class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-800 placeholder:text-slate-400 @error('email') border-red-500 @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@company.com"/>
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="flex flex-col gap-2 relative">
            <div class="flex justify-between items-center">
                <label class="text-sm font-bold text-slate-700" for="password">{{ __('Password') }}</label>
                 @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-primary hover:text-primary/80" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <div class="relative" x-data="{ show: false }">
                <input class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-800 placeholder:text-slate-400 pr-10 @error('password') border-red-500 @enderror" id="password" name="password" :type="show ? 'text' : 'password'" required autocomplete="current-password" placeholder="••••••••"/>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 !text-slate-400 cursor-pointer" @click="show = !show">visibility</span>
            </div>
             @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-3">
            <input class="size-4 rounded text-primary focus:ring-primary/20 border-slate-300" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}/>
            <label class="text-sm text-slate-600 font-medium" for="remember">
                {{ __('Keep me logged in') }}
            </label>
        </div>

        <button type="submit" class="w-full bg-primary text-white font-extrabold py-4 rounded-xl shadow-lg shadow-primary/20 hover:brightness-105 active:scale-[0.98] transition-all text-lg mt-4" data-test="login-button">
            {{ __('Log in') }}
        </button>
    </form>

    @if (Route::has('register'))
        <p class="text-center text-slate-600 font-medium">
            {{ __('New to Atomic Flow?') }} <a href="{{ route('register') }}" class="text-primary font-extrabold hover:underline" wire:navigate>{{ __('Create account') }}</a>
        </p>
    @endif
</x-layouts.auth-split>