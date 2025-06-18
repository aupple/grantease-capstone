@extends('layout')

@section('content')
<div class="form-box">
    <h1 class="title">GrantEase</h1>
    <p class="subtitle">Scholarship Management System</p>
    
    <h2>Sign in to your account</h2>
    <p class="hint">Access your scholarship application</p>

    <form>
        <input type="email" placeholder="Email Address">
        <div style="position: relative;">
            <input type="password" id="login-password" placeholder="Password">
            <span class="toggle-password" data-target="login-password" style="position: absolute; right: 10px; top: 12px; cursor: pointer;"></span>
        </div>

        <div style="text-align: right; margin-top: 0.5rem;">
            <a href="#">Forgot your password?</a>
        </div>

        <button type="button" onclick="alert('Login not implemented')">Sign In</button>

        <p>Don’t have an account? <a href="/register">Register Now</a></p>
    </form>
</div>
@endsection
