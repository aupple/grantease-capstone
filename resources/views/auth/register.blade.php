<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<<<<<<< HEAD
    <style>
        .header-wrapper {
            max-width: 447px;
            margin: 60px auto 0 auto;
            text-align: center;
        }

        .grant-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: rgb(3, 35, 104);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-bottom: 0.25rem;
        }

        .system-subtitle {
            font-size: 1rem;
            color: #6B7280;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .sign-in-heading {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
            text-align: center;
        }

        .sign-in-desc {
            font-size: 0.875rem;
            color: #4B5563;
            margin-bottom: 1.25rem;
            text-align: center;
        }

        .login-container {
            width: 447px;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin: 0 auto;
        }

        .form-group {
            text-align: left;
            margin-bottom: 0.75rem;
        }

        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
        }

        .input-icon-group {
            display: flex;
            align-items: center;
            border: 1px solid #D1D5DB;
            border-radius: 6px;
            background-color: #fff;
            padding: 0 0.5rem;
            margin-top: 0.25rem;
            position: relative;
        }

        .input-icon-group svg {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
            color: #6B7280;
        }

        .form-control {
            border: none;
            outline: none;
            width: 100%;
            padding: 0.55rem 0;
            font-size: 0.9rem;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6B7280;
        }

        .btn-login {
            width: 100%;
            background-color: rgb(3, 35, 104);
            color: white;
            border: none;
            padding: 0.6rem;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: rgb(2, 27, 85);
        }

        .register-link {
            text-align: center;
            font-size: 0.75rem;
            color: #6B7280;
        }

        .register-link a {
            color: rgb(3, 35, 104);
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>

    <!-- Header -->
    <div class="header-wrapper">
        <div class="grant-title">GrantEase</div>
        <div class="system-subtitle">Scholarship Management System</div>
    </div>

    <!-- Register Form -->
    <div class="login-container">
        <div class="sign-in-heading">Create your account</div>
        <div class="sign-in-desc">Access your scholarship applications</div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <div class="input-icon-group">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 100 8 4 4 0 000-8zM2 16a8 8 0 1116 0H2z" clip-rule="evenodd"/></svg>
                    <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-icon-group">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 6.9a2 2 0 012-2h16a2 2 0 012 2v.25L12 13 2.01 7.15V6.9zM2 9.42V17a2 2 0 002 2h16a2 2 0 002-2V9.42l-9.43 5.48a1 1 0 01-1.14 0L2 9.42z"/></svg>
                    <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon-group">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a4 4 0 00-4 4v4H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-8a2 2 0 00-2-2h-2V6a4 4 0 00-4-4zm-2 4a2 2 0 114 0v4h-4V6z" clip-rule="evenodd"/></svg>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                    <span class="toggle-password" onclick="togglePassword('password', 'eyeIcon1')">
                        <i id="eyeIcon1" class="fas fa-eye"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-icon-group">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a4 4 0 00-4 4v4H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-8a2 2 0 00-2-2h-2V6a4 4 0 00-4-4zm-2 4a2 2 0 114 0v4h-4V6z" clip-rule="evenodd"/></svg>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                    <span class="toggle-password" onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                        <i id="eyeIcon2" class="fas fa-eye"></i>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login">
                <i class="fas fa-user-plus mr-2"></i> Register
            </button>
        </form>

        <!-- Already Registered -->
        <div class="register-link mt-2">
            Already registered? <a href="{{ route('login') }}">Sign in here</a>
=======
        <!-- First Name -->
        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text"
                name="first_name"
                :value="old('first_name')"
                required
                autofocus
                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Middle Name -->
        <div class="mt-4">
            <x-input-label for="middle_name" :value="__('Middle Name')" />
            <x-text-input id="middle_name" class="block mt-1 w-full" type="text"
                name="middle_name"
                :value="old('middle_name')"
                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();" />
            <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text"
                name="last_name"
                :value="old('last_name')"
                required
                oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1).toLowerCase();" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
>>>>>>> d65b1dac2147ef244807d26b5537693ed11f0791
        </div>
    </div>

<<<<<<< HEAD
    <!-- JS for password toggle -->
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
=======
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
>>>>>>> d65b1dac2147ef244807d26b5537693ed11f0791
</x-guest-layout>
