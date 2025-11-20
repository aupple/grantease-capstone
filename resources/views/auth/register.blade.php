<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First Name -->
        <div>
            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');"
                placeholder="First Name"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                  bg-white/70 text-black-600 placeholder-black-600
                  focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('first_name')" class="mt-2 text-red-400" />
        </div>

        <!-- Middle Name -->
        <div class="mt-4">
            <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}"
                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');"
                placeholder="Middle Name (Optional)"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                  bg-white/70 text-black-600 placeholder-black-600
                  focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('middle_name')" class="mt-2 text-red-400" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                placeholder="Last Name"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                          bg-white/70 text-black-600 placeholder-black-600
                          focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('last_name')" class="mt-2 text-red-400" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username" placeholder="Email Address"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                          bg-white/70 text-black-600 placeholder-black-600
                          focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <div class="flex items-center space-x-2">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    placeholder="Password"
                    class="w-full px-4 py-3 rounded-full border border-white/20 
                      bg-white/70 text-black-600 placeholder-black-600
                      focus:outline-none focus:border-blue-400">

                <!-- Info Icon -->
                <button type="button" onclick="togglePasswordInfo()" class="text-white/70 hover:text-white transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>

            <!-- Password Requirements (Hidden by default) -->
            <div id="passwordInfo"
                class="hidden mt-2 text-xs text-white/80 bg-white/10 backdrop-blur-sm rounded-lg p-3 border border-white/20">
                <p class="font-bold mb-1 text-red-500">Password must include:</p>
                <ul class="space-y-1">
                    <li>✓ At least 8 characters</li>
                    <li>✓ Uppercase & lowercase letters</li>
                    <li>✓ At least one number</li>
                    <li>✓ At least one symbol (!@#$%^&*)</li>
                </ul>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <script>
            function togglePasswordInfo() {
                const info = document.getElementById('passwordInfo');
                info.classList.toggle('hidden');
            }
        </script>

        <!-- Confirm Password -->
        <div class="mt-4">
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="Confirm Password"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                  bg-white/70 text-black-600 placeholder-black-600
                  focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
        </div>

        <!-- Scholarship Program Selection -->
        <div class="mt-4">
            <select id="program_type" name="program_type" required
                class="w-full px-4 py-3 rounded-full border border-white/20 
                   bg-white/70 text-black-600 focus:outline-none focus:border-blue-400">
                <option value="">-- Select Scholarship Program --</option>
                <option value="DOST">DOST Applicant</option>
                <option value="CHED">CHED Scholar</option>
            </select>
            <x-input-error :messages="$errors->get('program_type')" class="mt-2 text-red-400" />
        </div>

        <!-- Full-width Register Button -->
        <div class="mt-6">
            <x-primary-button class="w-full justify-center text-white border-none" style="background-color: #1e33a3;">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <!-- Already registered link -->
        <p class="mt-4 text-center text-sm text-white/90">
            Already have an account?
            <a href="{{ route('login') }}" class="underline text-blue-300 hover:text-white">
                Login here
            </a>
        </p>
    </form>
</x-guest-layout>
