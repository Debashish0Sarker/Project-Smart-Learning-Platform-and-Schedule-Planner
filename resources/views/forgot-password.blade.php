@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')

<h2>Forgot Password</h2>

@if (session('status'))
    <p style="color: green;">{{ session('status') }}</p>
@endif

@if ($errors->any())
    <p style="color: red;">{{ $errors->first() }}</p>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <label>Email:</label>
    <input type="email" name="email" required>

    <button type="submit">Send Reset Link</button>
</form>

@endsection
