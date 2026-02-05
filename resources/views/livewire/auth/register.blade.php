<x-layouts.auth-split>
    <div class="lg:hidden flex items-center gap-2 mb-4">
        <div class="size-10 bg-primary rounded-xl flex items-center justify-center text-white">
            <span class="material-symbols-outlined !text-2xl">folder_managed</span>
        </div>
        <h2 class="text-2xl font-extrabold tracking-tight font-display text-slate-800">Pro-<span class="text-primary">File</span></h2>
    </div>

    <div class="flex flex-col gap-2">
        <h2 class="text-3xl font-extrabold font-display text-slate-900">{{ __('Create your account') }}</h2>
        <p class="text-slate-500 font-medium">{{ __('Start managing your assets more efficiently today.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="grid grid-cols-2 gap-4">
        <button class="flex items-center justify-center gap-3 px-4 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-slate-700 text-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"></path>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
            </svg>
            Google
        </button>
        <button class="flex items-center justify-center gap-3 px-4 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-slate-700 text-sm">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"></path>
            </svg>
            GitHub
        </button>
    </div>

    <div class="relative flex items-center justify-center">
        <div class="absolute w-full border-t border-slate-100"></div>
        <span class="relative bg-white px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Or with email') }}</span>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
        @csrf
        
        <!-- Name -->
        <div class="flex flex-col gap-2">
            <label class="text-sm font-bold text-slate-700" for="name">{{ __('Full Name') }}</label>
            <input class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-800 placeholder:text-slate-400 @error('name') border-red-500 @enderror" id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe"/>
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="flex flex-col gap-2">
            <label class="text-sm font-bold text-slate-700" for="email">{{ __('Work Email') }}</label>
            <input class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-800 placeholder:text-slate-400 @error('email') border-red-500 @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@company.com"/>
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="flex flex-col gap-2 relative">
            <div class="flex justify-between items-center">
                <label class="text-sm font-bold text-slate-700" for="password">{{ __('Password') }}</label>
                <span class="text-xs text-slate-400 font-medium">{{ __('Min. 8 characters') }}</span>
            </div>
            <div class="relative" x-data="{ show: false }">
                <input class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-800 placeholder:text-slate-400 pr-10 @error('password') border-red-500 @enderror" id="password" name="password" :type="show ? 'text' : 'password'" required autocomplete="new-password" placeholder="••••••••"/>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 !text-slate-400 cursor-pointer" @click="show = !show">visibility</span>
            </div>
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="flex flex-col gap-2 relative">
            <label class="text-sm font-bold text-slate-700" for="password_confirmation">{{ __('Confirm Password') }}</label>
            <div class="relative" x-data="{ show: false }">
                <input class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-800 placeholder:text-slate-400 pr-10" id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" required autocomplete="new-password" placeholder="••••••••"/>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 !text-slate-400 cursor-pointer" @click="show = !show">visibility</span>
            </div>
        </div>

        <!-- Terms -->
        <div class="flex items-start gap-3 mt-2">
            <input class="mt-1 size-4 rounded text-primary focus:ring-primary/20 border-slate-300" id="terms" name="terms" type="checkbox"/>
            <label class="text-xs text-slate-500 leading-normal" for="terms">
                {{ __('By creating an account, you agree to our') }} <a class="text-primary font-bold hover:underline" href="#">{{ __('Terms of Service') }}</a> {{ __('and') }} <a class="text-primary font-bold hover:underline" href="#">{{ __('Privacy Policy') }}</a>.
            </label>
        </div>

        <button type="submit" class="w-full bg-primary text-white font-extrabold py-4 rounded-xl shadow-lg shadow-primary/20 hover:brightness-105 active:scale-[0.98] transition-all text-lg mt-4" data-test="register-user-button">
            {{ __('Create Account') }}
        </button>
    </form>

    <p class="text-center text-slate-600 font-medium">
        {{ __('Already have an account?') }} <a class="text-primary font-extrabold hover:underline" href="{{ route('login') }}" wire:navigate>{{ __('Log in') }}</a>
    </p>
</x-layouts.auth-split>