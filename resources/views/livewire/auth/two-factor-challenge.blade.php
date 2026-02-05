<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;
                    this.code = '';
                    this.recovery_code = '';
                    $dispatch('clear-2fa-auth-code');
                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <x-auth-header
                    :title="__('Authentication code')"
                    :description="__('Enter the authentication code provided by your authenticator application.')"
                />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header
                    :title="__('Recovery code')"
                    :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')"
                />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}" class="flex flex-col gap-6">
                @csrf

                <div x-show="!showRecoveryInput" class="flex flex-col gap-6">
                    <div class="flex items-center justify-center">
                        <x-input-otp
                            name="code"
                            digits="6"
                            autocomplete="one-time-code"
                            x-model="code"
                        />
                    </div>

                    @error('code')
                        <flux:text color="red" class="text-center">
                            {{ $message }}
                        </flux:text>
                    @enderror
                </div>

                <div x-show="showRecoveryInput" class="flex flex-col gap-6">
                    <flux:input
                        :label="__('Recovery code')"
                        type="text"
                        name="recovery_code"
                        x-ref="recovery_code"
                        x-bind:required="showRecoveryInput"
                        autocomplete="one-time-code"
                        x-model="recovery_code"
                        placeholder="XXXXXX-XXXXXX"
                    />

                    @error('recovery_code')
                        <flux:text color="red" class="text-center">
                            {{ $message }}
                        </flux:text>
                    @enderror
                </div>

                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Continue') }}
                </flux:button>
            </form>

            <div class="mt-6 text-center text-sm text-zinc-600 dark:text-zinc-400">
                <span class="mr-1">{{ __('Or you can') }}</span>
                <button
                    type="button"
                    class="cursor-pointer underline hover:text-zinc-900 dark:hover:text-white transition-colors"
                    @click="toggleInput()"
                >
                    <span x-show="!showRecoveryInput">{{ __('login using a recovery code') }}</span>
                    <span x-show="showRecoveryInput">{{ __('login using an authentication code') }}</span>
                </button>
            </div>
        </div>
    </div>
</x-layouts.auth>
