{{-- resources/views/quiz_pending.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $quiz->title }}</h2>
    <p>{{ $message }}</p>

    <h4>Submitted Answers:</h4>
    <ul>
        @foreach($details as $detail)
            <li>
                Question ID {{ $detail['question_id'] }}:
                Selected: {{ $detail['selected'] ?? 'No answer' }},
                Status: {{ $detail['status'] }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
