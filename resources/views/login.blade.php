@extends('layouts.app')

@section('title', 'Login')

@section('content')

<form action="/login" method="POST">
    @if ($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if (session('success'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @csrf
    <h2>Login</h2>
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <a href="/forgot-password">Forgot Password?</a>
    <br><br>
    <button type="submit">Login</button>
</form>

@endsection
