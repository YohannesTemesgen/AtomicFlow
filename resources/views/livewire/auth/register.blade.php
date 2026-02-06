<x-layouts.auth-split>
    <div class="lg:hidden flex items-center gap-2 mb-4">
        <div class="size-10 bg-primary rounded-xl flex items-center justify-center text-white">
            <span class="material-symbols-outlined !text-2xl">folder_managed</span>
        </div>
        <h2 class="text-2xl font-extrabold tracking-tight font-display text-slate-800">Atomic <span class="text-primary">Flow</span></h2>
    </div>

    <div class="flex flex-col gap-2">
        <h2 class="text-3xl font-extrabold font-display text-slate-900">{{ __('Create your account') }}</h2>
        <p class="text-slate-500 font-medium">{{ __('Start managing your projects and leads more efficiently today.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

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

        <!-- Company Name -->
        <div class="flex flex-col gap-2">
            <label class="text-sm font-bold text-slate-700" for="company_name">{{ __('Company Name') }}</label>
            <input class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-800 placeholder:text-slate-400 @error('company_name') border-red-500 @enderror" id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required autocomplete="organization" placeholder="Acme Inc."/>
            @error('company_name')
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
            <div class="flex justify-between items-center">
                <label class="text-sm font-bold text-slate-700" for="password_confirmation">{{ __('Confirm Password') }}</label>
            </div>
            <div class="relative" x-data="{ show: false }">
                <input class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all text-slate-800 placeholder:text-slate-400 pr-10" id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" required autocomplete="new-password" placeholder="••••••••"/>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 !text-slate-400 cursor-pointer" @click="show = !show">visibility</span>
            </div>
        </div>

        <!-- Terms -->
        <div class="flex items-start gap-3 mt-2">
            <input class="mt-1 size-4 rounded text-primary focus:ring-primary/20 border-slate-300" id="terms" name="terms" type="checkbox" required/>
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