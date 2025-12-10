@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<h1>{{ $quiz->title }}</h1>
<p>{{ $quiz->description }}</p>
<p>Difficulty: {{ ucfirst($quiz->difficulty) }}</p>
<p>Duration: {{ $quiz->duration_minutes }} minutes</p>

<form action="/student/quiz/{{ $quiz->id }}/submit" method="POST">
    @csrf
    @foreach($questions as $question)
        <div style="margin-bottom: 20px; padding:10px; border:1px solid #ccc;">
            <p><strong>Q{{ $loop->iteration }}: {{ $question->question_text }}</strong></p>

            @if($question->question_type === 'mcq' || $question->question_type === 'true_false')
                @php
                    $options = json_decode($question->options, true);
                @endphp
                @foreach($options as $option)
                    <label>
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}">
                        {{ $option }}
                    </label><br>
                @endforeach
            @elseif($question->question_type === 'short_answer')
                <input type="text" name="answers[{{ $question->id }}]" style="width: 100%;">
            @endif
        </div>
    @endforeach

    <button type="submit">Submit Quiz</button>
</form>
@endsection
