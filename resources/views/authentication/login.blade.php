@extends('layouts.sign')

@section('content')
<div class="login-page">
    <div class="form-box">
        <h1 class="title">GrantEase</h1>
        <p class="subtitle">Graduate Scholarship Management System</p>
        
        <h2>Sign in to your account</h2>
        <p class="hint">Access your scholarship application</p>

        <form onsubmit="return false;">
            <input type="email" id="email" placeholder="Email Address" required>
            <input type="password" id="password" placeholder="Password" required>

            <div style="text-align: right; margin-top: 0.5rem;">
                <a href="/forgot">Forgot your password?</a>
            </div>

            <button id="signin-btn" type="button" onclick="checkLogin()">Sign In</button>

            <p>Don’t have an account? <a href="/register">Register Now</a></p>
        </form>
    </div>
</div>

<script>
    function checkLogin() {
        const email = document.getElementById('email').value.trim().toLowerCase();
        const password = document.getElementById('password').value.trim();
        const button = document.getElementById('signin-btn');

        if (!email || !password) {
            alert('Please fill in both Email and Password.');
            return;
        }

        // Disable button and show loading text
        button.disabled = true;
        button.textContent = 'Loading...';

        // Simulate login delay
        setTimeout(() => {
            if (email === 'admin@grantease.com') {
                window.location.href = '/admin/dashboard';
            } else {
                window.location.href = '/applicant/home';
            }
        }, 1200);
    }
</script>
@endsection
