@extends('layout')

@section('content')
<div class="form-box">
    <h1 class="title">GrantEase</h1>
    <p class="subtitle">Scholarship Management System</p>
    <h2>Create your account</h2>
    <p class="hint">Verify Email</p>

    <form>
        <p>We’ve sent a verification code to <strong>your@email.com</strong></p>
        <input type="text" placeholder="Enter 6-digit code">

        <button type="button" onclick="alert('OTP Verified')">Verify OTP</button>
        <p>Didn’t receive a code? <a href="#">Resend OTP</a></p>
        <p>Already have an account? <a href="/login">Sign In</a></p>
    </form>
</div>
@endsection
