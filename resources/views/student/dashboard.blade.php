@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')

<h1>Student Dashboard</h1>
<p>Welcome, {{ auth()->user()->name }}!</p>

<form action="/logout" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>

<h2>Available Quizzes</h2>

@foreach($quizzes as $quiz)
    <a href="{{ url('/student/quiz/'.$quiz->id.'/questions') }}">
        Start {{ $quiz->title }}
    </a><br>
@endforeach




@endsection
