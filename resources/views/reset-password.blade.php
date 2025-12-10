@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<h2>Reset Your Password</h2>

@if ($errors->any())
    <div style="color:red;">
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

@if (session('status'))
    <div style="color:green;">{{ session('status') }}</div>
@endif

<form action="{{ route('password.store') }}" method="POST">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ request()->query('email') }}">

    <label>New Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="password_confirmation" required><br><br>

    <button type="submit">Reset Password</button>
</form>

@endsection
