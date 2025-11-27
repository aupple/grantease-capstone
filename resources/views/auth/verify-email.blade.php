<x-guest-layout>
    @if (auth()->user()->hasVerifiedEmail())
        {{-- Already Verified - Show Success and Auto-Redirect --}}
        <div class="text-center">
            <!-- Success Animation -->
            <div class="mb-6 flex justify-center">
                <div class="relative">
                    <svg class="h-24 w-24 text-green-500 animate-bounce" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <h2 class="text-3xl font-bold text-white mb-3">Email Verified!</h2>
            <p class="text-white/90 text-lg mb-2">Your email has been successfully verified.</p>
            <p class="text-white/70 text-sm">Redirecting to login page...</p>

            <!-- Loading Animation -->
            <div class="mt-8 flex justify-center">
                <div class="flex space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                    <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                    <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
                </div>
            </div>

            <!-- Manual Redirect Link -->
            <div class="mt-8">
                <a href="{{ route('login') }}" class="text-sm text-blue-300 hover:text-white underline">
                    Click here if not redirected automatically
                </a>
            </div>
        </div>

        <!-- Auto-redirect Script -->
        <script>
            // Logout user first, then redirect
            fetch('{{ route('logout') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            }).then(() => {
                // Redirect to login after 2 seconds
                setTimeout(() => {
                    window.location.href = '{{ route('login') }}?verified=1';
                }, 2000);
            });
        </script>
    @else
        {{-- Not Verified Yet - Show Instructions --}}
        <div class="text-center mb-6">
            <div class="mb-4">
                <svg class="mx-auto h-16 w-16 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Verify Your Email</h2>
            <p class="text-white/90">Please check your inbox and verify your email address</p>
        </div>

        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl p-6 mb-6">
            <p class="text-white/90 text-sm text-center">
                We've sent a verification link to <strong class="text-white">{{ auth()->user()->email }}</strong>
            </p>
            <p class="text-white/80 text-xs text-center mt-2">
                Please check your inbox (and spam folder) and click the verification link to activate your account.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div
                class="mb-4 bg-green-500/30 border border-green-400/50 text-white px-4 py-3 rounded-lg backdrop-blur-sm">
                <p class="text-sm font-medium text-center">
                    ✅ A new verification link has been sent to your email!
                </p>
            </div>
        @endif

        <div class="space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full py-3 rounded-lg text-white font-semibold 
                               bg-gradient-to-r from-blue-500 to-blue-700 
                               hover:from-blue-600 hover:to-blue-800 shadow-lg transition">
                    📧 Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full py-3 rounded-lg text-white/90 font-medium 
                               bg-white/10 hover:bg-white/20 border border-white/20 transition">
                    ← Back to Login
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs text-white/70">
                💡 Tip: Check your spam/junk folder if you don't see the email
            </p>
        </div>
    @endif
</x-guest-layout>
