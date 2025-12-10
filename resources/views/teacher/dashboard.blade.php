@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Teacher Dashboard</h1>
        <p class="text-gray-600 mt-2">Smart Learning Platform - Development Mode</p>
        <div class="mt-2 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-2 rounded">
            ⚠️ Development Mode: Authentication is disabled for testing
        </div>
    </div>
    
    <!-- Development Mode Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Courses</h3>
            <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Course::count() }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Quizzes</h3>
            <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Quiz::count() }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Questions</h3>
            <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Question::count() }}</p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('teacher.quizzes.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
                Create New Quiz
            </a>
            <a href="{{ route('teacher.quizzes.index') }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold">
                View All Quizzes
            </a>
            <a href="{{ route('teacher.courses.index') }}" 
               class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold">
                View Courses
            </a>
        </div>
    </div>
    
    <!-- Feature 3 Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Module 1 - Feature 3</h2>
        <div class="bg-blue-50 p-6 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Teachers can create quizzes and questions with topic tags</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">📋 Requirements:</h4>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <span>Teacher selects course</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <span>Define quiz title, description, difficulty</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <span>Add multiple questions with topic tags</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-500 mr-2">✓</span>
                            <span>Validation for correct answers</span>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">🎯 Test Now:</h4>
                    <div class="space-y-2">
                        <a href="{{ route('teacher.quizzes.create') }}" 
                           class="block bg-blue-100 hover:bg-blue-200 text-blue-800 px-4 py-2 rounded">
                            1. Create a Quiz
                        </a>
                        <a href="/feature3-test" 
                           class="block bg-green-100 hover:bg-green-200 text-green-800 px-4 py-2 rounded">
                            2. System Check
                        </a>
                        <a href="/check-tables" 
                           class="block bg-purple-100 hover:bg-purple-200 text-purple-800 px-4 py-2 rounded">
                            3. Database Status
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Quizzes -->
            @php
                $recentQuizzes = \App\Models\Quiz::with('course')->latest()->take(3)->get();
            @endphp
            
            @if($recentQuizzes->count() > 0)
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-700 mb-2">📝 Recent Quizzes:</h4>
                    <div class="space-y-2">
                        @foreach($recentQuizzes as $quiz)
                            <div class="border border-gray-200 rounded p-3">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ $quiz->title }}</span>
                                    <span class="text-sm text-gray-500">{{ $quiz->course->code }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 mt-1">
                                    <span>{{ $quiz->questions->count() }} questions</span>
                                    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="text-blue-600">View →</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection