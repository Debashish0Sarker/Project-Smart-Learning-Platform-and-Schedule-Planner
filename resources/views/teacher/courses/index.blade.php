@extends('layouts.teacher')

@section('title', 'My Courses')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Courses</h1>
    
    @if($courses->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500">No courses assigned to you.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $course->code }}</h3>
                    <h4 class="text-gray-700 mb-2">{{ $course->title }}</h4>
                    
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <div>
                            <span>{{ $course->quizzes->count() }} quizzes</span>
                        </div>
                        <div>
                            <span>{{ $course->students->count() }} students</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('teacher.quizzes.create') }}?course_id={{ $course->id }}" 
                       class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-md">
                        Add Quiz to this Course
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection