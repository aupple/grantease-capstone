@extends('layout')

@section('content')
<div class="form-box">
    <h1 class="title">GrantEase</h1>
    <p class="subtitle">Scholarship Management System</p>
    <h2>Create your account</h2>
    <p class="hint">Enter your email and details below</p>

    <form>
        <input type="text" placeholder="Full Name">
        <input type="email" placeholder="Email Address">
        <input type="text" placeholder="Mobile Number">
        <input type="password" placeholder="Password">
        <input type="password" placeholder="Confirm Password">

        <button type="button" onclick="window.location.href='/verify'">Register</button>
        <p>Already have an account? <a href="/login">Sign In</a></p>
    </form>
</div>
@endsection
