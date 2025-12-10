@extends('layouts.teacher')

@section('title', 'View Quiz')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
                <p class="text-gray-600 mt-2">Course: {{ $quiz->course->code }} - {{ $quiz->course->title }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('teacher.quizzes.edit', $quiz) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    Edit Quiz
                </a>
                <a href="{{ route('teacher.quizzes.index') }}" 
                   class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md">
                    Back to List
                </a>
            </div>
        </div>
    </div>
    
    <!-- Quiz Details -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="text-center p-4 bg-gray-50 rounded">
                <div class="text-sm text-gray-500">Total Points</div>
                <div class="text-2xl font-bold text-gray-800">{{ $quiz->total_points }}</div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded">
                <div class="text-sm text-gray-500">Questions</div>
                <div class="text-2xl font-bold text-gray-800">{{ $quiz->questions->count() }}</div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded">
                <div class="text-sm text-gray-500">Difficulty</div>
                <div class="text-2xl font-bold text-gray-800">{{ ucfirst($quiz->difficulty) }}</div>
            </div>
        </div>
        
        @if($quiz->description)
            <div class="mb-4">
                <h3 class="font-semibold text-gray-700 mb-2">Description:</h3>
                <p class="text-gray-600">{{ $quiz->description }}</p>
            </div>
        @endif
    </div>
    
    <!-- Questions List -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Questions</h2>
        
        @foreach($quiz->questions as $index => $question)
            <div class="border border-gray-200 rounded-lg p-6 mb-4">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-700">
                            Question {{ $index + 1 }}
                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ $question->topic_tag }}
                            </span>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                {{ $question->points }} points
                            </span>
                        </h3>
                        <p class="mt-2 text-gray-800">{{ $question->question_text }}</p>
                    </div>
                </div>
                
                @if($question->question_type === 'mcq' && $question->options)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Options:</h4>
                        <div class="space-y-2">
                            @foreach($question->options as $key => $option)
                                <div class="flex items-center space-x-2">
                                    @if(in_array($key, $question->correct_answers ?? []))
                                        <span class="text-green-500 font-bold">✓</span>
                                    @endif
                                    <span class="{{ in_array($key, $question->correct_answers ?? []) ? 'text-green-600 font-medium' : 'text-gray-700' }}">
                                        {{ $key }}. {{ $option }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($question->question_type === 'true_false')
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Correct Answer:</h4>
                        <div class="flex items-center space-x-2">
                            <span class="text-green-500 font-bold">✓</span>
                            <span class="text-green-600 font-medium">
                                {{ ucfirst($question->correct_answers[0] ?? '') }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Expected Answer:</h4>
                        <div class="bg-gray-50 p-3 rounded">
                            <p class="text-gray-800">{{ $question->correct_answers[0] ?? '' }}</p>
                        </div>
                    </div>
                @endif
                
                @if($question->explanation)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Explanation:</h4>
                        <p class="text-gray-600">{{ $question->explanation }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection