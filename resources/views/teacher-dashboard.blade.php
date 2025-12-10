@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')

<h1>Teacher Dashboard</h1>
<p>Welcome, {{ auth()->user()->name }}!</p>

<form action="/logout" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>

@endsection
