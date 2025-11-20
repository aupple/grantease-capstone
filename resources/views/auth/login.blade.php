<x-guest-layout>
    <!-- Email Verified Success Message -->
    @if (session('verified') || request()->get('verified'))
        <div class="mb-4 bg-green-500/30 border border-green-400/50 text-white px-4 py-3 rounded-lg backdrop-blur-sm">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium">Email verified successfully! You can now log in to your account.</p>
            </div>
        </div>
    @endif

    <!-- Session Status (only show if NOT verified message) -->
    @if (session('status') && !session('verified') && !request()->get('verified'))
        <x-auth-session-status class="mb-3" :status="session('status')" />
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="Email Address"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                          bg-white/70 text-gray-400 placeholder-gray-400
                          focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="Password"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                          bg-white/70 text-gray-400 placeholder-gray-400
                          focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me + Forgot Password -->
        <div class="flex items-center justify-between mt-4 text-white/90">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-blue-500 shadow-sm focus:ring-0" name="remember">
                <span class="ms-2 text-sm">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-blue-300 hover:text-white underline">
                    Forgot your password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="mt-6">
            <button type="submit"
                class="w-full py-3 rounded-lg text-white font-semibold 
                           bg-gradient-to-r from-blue-500 to-blue-700 
                           hover:from-blue-600 hover:to-blue-800 shadow-lg">
                Log in
            </button>
        </div>
    </form>

    <!-- Register Link -->
    <p class="mt-6 text-center text-sm text-white/90">
        Don't have an account?
        <a href="{{ route('register') }}" class="underline text-blue-300 hover:text-white">
            Register here
        </a>
    </p>
</x-guest-layout>
