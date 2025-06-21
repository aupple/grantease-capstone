@extends('layouts.sign')

@section('content')
<div class="login-page">
    <div class="form-box">
        <h1 class="title">GrantEase</h1>
        <p class="subtitle">Graduate Scholarship Management System</p>
        
        <h2>Create your account</h2>
        <p class="hint">Enter your email and details below</p>

        <form>
            <input type="text" placeholder="Full Name" required>
            <input type="email" placeholder="Email Address" required>
            <input type="text" placeholder="Mobile Number" required>
            <input type="password" placeholder="Password" required>
            <input type="password" placeholder="Confirm Password" required>

            <button type="button" onclick="window.location.href='/verify'">Register</button>

            <p>Already have an account? <a href="/login">Sign In</a></p>
        </form>
    </div>
</div>
@endsection
