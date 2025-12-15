@extends('layouts.app')

@section('title', 'Quiz Result')

@section('content')
    <h2>{{ $quiz->title }} - Result</h2>

    <p>Score: {{ $score }} / {{ $total }}</p>
    <p>Percentage: {{ $percentage }}%</p>

    <h3>Details:</h3>
    <ul>
        @foreach($details as $detail)
            <li>
                Q{{ $detail['question_id'] }}: Selected "{{ $detail['selected'] }}" |
                Correct: "{{ implode(', ', $detail['correct']) }}" |
                Status: {{ $detail['status'] }}
            </li>
        @endforeach
    </ul>

    <a href="{{ url('/student/dashboard') }}">Back to Dashboard</a>
@endsection
