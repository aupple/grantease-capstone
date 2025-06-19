@extends('layout')

@section('content')
<div class="login-page">
    <div class="form-box">
        <h1 class="title">GrantEase</h1>
        <p class="subtitle">Scholarship Management System</p>
        
        <h2>Forgot Password</h2>
        <p class="hint">Enter your email address to reset your password.</p>

        <form>
            <input type="email" placeholder="Email Address">
            <button type="button" onclick="alert('Reset link sent!')">Send Reset Link</button>

            <p><a href="/login">← Back to Login</a></p>
        </form>
    </div>
</div>
@endsection
