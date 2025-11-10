<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First Name -->
        <div>
            <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                placeholder="First Name"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                          bg-white/70 text-black-600 placeholder-black-600
                          focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('first_name')" class="mt-2 text-red-400" />
        </div>

        <!-- Middle Name -->
        <div class="mt-4">
            <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name') }}"
                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();"
                placeholder="Middle Name"
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
        <div class="mt-4">
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="Password"
                class="w-full px-4 py-3 rounded-full border border-white/20 
                          bg-white/70 text-black-600 placeholder-black-600
                          focus:outline-none focus:border-blue-400">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

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
