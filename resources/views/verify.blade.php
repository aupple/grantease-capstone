@extends('layout')

@section('content')
<div class="login-page">
    <div class="form-box">
        <h1 class="title">GrantEase</h1>
        <p class="subtitle">Scholarship Management System</p>

        <h2>Create your account</h2>
        <p class="hint">Verify Email</p>

        <form>
            <p>We’ve sent a verification code to <strong>your@email.com</strong></p>
            
            <input type="text" placeholder="Enter 6-digit code" maxlength="6" required>

            <button type="button" onclick="alert('OTP Verified')">Verify OTP</button>
            
            <p>
                Didn’t receive a code? 
                <a href="#" onclick="resendOTP()">Resend OTP</a>
            </p>

            <p>Already have an account? <a href="/login">Sign In</a></p>
        </form>
    </div>
</div>

<script>
    function resendOTP() {
        alert('OTP has been resent to your email.');
        // You can replace the alert with actual AJAX or redirect later
    }
</script>
@endsection
