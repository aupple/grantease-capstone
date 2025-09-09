<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   placeholder="Email Address"
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
                       class="rounded border-gray-300 text-blue-500 shadow-sm focus:ring-0" 
                       name="remember">
                <span class="ms-2 text-sm">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                   class="text-sm text-blue-300 hover:text-white underline">
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
        <a href="{{ route('register') }}" 
           class="underline text-blue-300 hover:text-white">
            Register here
        </a>
    </p>
</x-guest-layout>
