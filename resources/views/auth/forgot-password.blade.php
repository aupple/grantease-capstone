<x-guest-layout>
    <style>
        .reset-container {
            width: 447px;
            margin: 70px auto;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .reset-description {
            font-size: 0.875rem;
            color: #4B5563;
        }

        .input-label {
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

        .btn-reset {
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

        .btn-reset:hover {
            background-color: #1D4ED8;
        }
    </style>

    <div class="reset-container">
        <div class="reset-description">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-2" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Input -->
            <label for="email" class="input-label">Email Address</label>
            <div class="input-icon-group">
                <!-- Email Icon -->
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 6.9a2 2 0 012-2h16a2 2 0 012 2v.25L12 13 2.01 7.15V6.9zM2 9.42V17a2 2 0 002 2h16a2 2 0 002-2V9.42l-9.43 5.48a1 1 0 01-1.14 0L2 9.42z"/></svg>
                <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />

            <!-- Submit Button -->
            <button type="submit" class="btn-reset mt-2">
                Email Password Reset Link
            </button>
        </form>
    </div>
</x-guest-layout>
