@extends('layouts.teacher')

@section('title', 'Submission Details')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Submission Details</h1>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-lg font-semibold">{{ $quizResponse->user->name ?? 'Student' }}</div>
                <div class="text-sm text-gray-500">{{ $quizResponse->user->email ?? '' }}</div>
            </div>

            <div class="text-right text-sm text-gray-600">
                <div>Quiz: <span class="font-medium">{{ $quizResponse->quiz->title ?? 'Quiz' }}</span></div>
                <div>Course: <span class="font-medium">{{ $quizResponse->quiz->course->title ?? 'Course' }}</span></div>
                <div>Submitted: <span class="font-medium">{{ $quizResponse->submitted_at ? $quizResponse->submitted_at->format('Y-m-d H:i') : 'Not submitted' }}</span></div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 rounded">
                <div class="text-sm text-gray-500">Score</div>
                <div class="text-xl font-semibold">{{ $quizResponse->score ?? '—' }}</div>
            </div>
            <div class="p-4 bg-gray-50 rounded">
                <div class="text-sm text-gray-500">Percentage</div>
                <div class="text-xl font-semibold">{{ $quizResponse->percentage ? number_format($quizResponse->percentage, 2) . '%' : '—' }}</div>
            </div>
            <div class="p-4 bg-gray-50 rounded">
                <div class="text-sm text-gray-500">Status</div>
                <div class="text-xl font-semibold">{{ $quizResponse->getStatusText() }}</div>
            </div>
        </div>

        @if(!empty($quizResponse->feedback))
            <div class="mt-4">
                <h3 class="font-semibold">Feedback</h3>
                <p class="text-sm text-gray-700">{{ $quizResponse->feedback }}</p>
            </div>
        @endif
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Answers</h2>

        @if($quizResponse->quizAnswers && $quizResponse->quizAnswers->count() > 0)
            <div class="space-y-4">
                @foreach($quizResponse->quizAnswers as $answer)
                    <div class="border rounded p-4">
                        <div class="flex justify-between items-start">
                            <div class="max-w-3xl">
                                <div class="font-medium">Q: {{ $answer->question->question_text ?? 'Question' }}</div>
                                <div class="text-sm text-gray-600 mt-1">Your answer: <span class="font-medium">{{ is_array($answer->answer_given) ? implode(', ', $answer->answer_given) : $answer->answer_given }}</span></div>
                                @if(isset($answer->question->correct_answer))
                                    <div class="text-sm text-gray-500 mt-1">Correct answer: {{ is_array($answer->question->correct_answer) ? implode(', ', $answer->question->correct_answer) : $answer->question->correct_answer }}</div>
                                @endif
                            </div>

                            <div class="text-sm">
                                @if($answer->is_correct)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded">Correct</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded">Incorrect</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500">No answers recorded for this submission.</div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('teacher.quizzes.submissions', $quizResponse->quiz) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded">Back to Submissions</a>
    </div>
</div>
@endsection
