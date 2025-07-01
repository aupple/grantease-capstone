<x-guest-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

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

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .form-options label {
            color: #4B5563;
        }

        .form-options a {
            color: rgb(3, 35, 104);
            text-decoration: none;
        }

        .form-options a:hover {
            text-decoration: underline;
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
            background-color: rgb(3, 35, 104);
        }

        .register-link {
            text-align: center;
            font-size: 0.75rem;
            color: #6B7280;
        }

        .register-link a {
            color:rgb(3, 35, 104);
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>

    <!-- GrantEase Header -->
    <div class="header-wrapper">
        <div class="grant-title">GrantEase</div>
        <div class="system-subtitle">Scholarship Management System</div>
    </div>

    <!-- Login Card -->
    <div class="login-container">
        <!-- Sign In Text Inside Card -->
        <div class="sign-in-heading">Sign in to your account</div>
        <div class="sign-in-desc">Access your scholarship applications</div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-icon-group">
                    <!-- User Icon -->
                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 100 8 4 4 0 000-8zM2 16a8 8 0 1116 0H2z" clip-rule="evenodd"/>
                    </svg>
                    <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon-group">
                    <!-- Lock Icon -->
                    <svg fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M12 2a4 4 0 00-4 4v4H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-8a2 2 0 00-2-2h-2V6a4 4 0 00-4-4zm-2 4a2 2 0 114 0v4h-4V6z" clip-rule="evenodd"/>
                    </svg>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember + Forgot -->
            <div class="form-options">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-1">
                    Remember me
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <!-- Button -->
             <button type="submit" class="btn-login">
               <i class="fas fa-right-to-bracket" style="margin-right: 8px;"></i> Log In
             </button>

        </form>

        <!-- Register Link -->
        <div class="register-link mt-2">
            Don’t have an account? <a href="{{ route('register') }}">Register here</a>
        </div>
    </div>
</x-guest-layout>
