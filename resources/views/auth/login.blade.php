<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

<!-- Remember Me + Forgot Password in One Row -->
<div class="flex items-center justify-between mt-4">
    <label for="remember_me" class="inline-flex items-center">
        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
    </label>

    @if (Route::has('password.request'))
        <a class="text-sm text-blue-600 hover:text-gray-900 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
        </a>
    @endif
</div>

<!-- Full-width Login Button -->
<div class="mt-4">
    <x-primary-button class="w-full justify-center bg-blue-600 hover:bg-blue-700 text-white border-none">
    {{ __('Log in') }}
</x-primary-button>

</div>




    <p class="mt-4 text-center text-sm text-gray-600">
        {{ __("Don't have an account?") }}
        <a href="{{ route('register') }}" class="underline text-blue-600 hover:text-blue-900">
            {{ __('Register here') }}
        </a>
    </p>
</x-guest-layout>
