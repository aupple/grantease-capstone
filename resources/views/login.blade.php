@extends('layout')

@section('content')
<div class="login-page">
    <div class="form-box">
        <h1 class="title">GrantEase</h1>
        <p class="subtitle">Scholarship Management System</p>
        
        <h2>Sign in to your account</h2>
        <p class="hint">Access your scholarship application</p>

        <form>
            <input type="email" placeholder="Email Address" required>

            <div>
                <input type="password" placeholder="Password" required>
            </div>

            <div style="text-align: right; margin-top: 0.5rem;">
                <a href="/forgot">Forgot your password?</a>
            </div>

            <button type="button" onclick="window.location.href='/applicant/home'">Sign In</button>

            <p>Don’t have an account? <a href="/register">Register Now</a></p>
        </form>
    </div>
</div>
@endsection
