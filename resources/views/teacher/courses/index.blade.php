@extends('layouts.teacher')

@section('title', 'My Courses')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Courses</h1>
        <a href="{{ route('teacher.courses.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
            + Create New Course
        </a>
    </div>
    
    @if($courses->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500 mb-4">No courses assigned to you.</p>
            <a href="{{ route('teacher.courses.create') }}" 
               class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-md">
                Create Your First Course
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $course->code }}</h3>
                        <h4 class="text-gray-700 mb-2">{{ $course->title }}</h4>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($course->status === 'published') bg-green-100 text-green-800
                            @elseif($course->status === 'draft') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($course->status) }}
                        </span>
                    </div>
                    
                    {{--<div class="flex items-center justify-between text-sm text-gray-500 mb-6">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-users"></i>
                                {{ $course->students->count() ?? 0 }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-question-circle"></i>
                                {{ $course->quizzes->count() }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-file-alt"></i>
                                {{ $course->materials->count() }}
                            </span>
                        </div>
                    </div>--}}
                    
                    <!-- Action Buttons Grid -->
                    <div class="grid grid-cols-2 gap-2">
                        <!-- View Course -->
                        <a href="{{ route('teacher.courses.show', $course) }}" 
                           class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded text-center text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i> View
                        </a>
                        
                        <!-- View Materials -->
                        <a href="{{ route('teacher.courses.materials.index', $course->id) }}" 
                           class="bg-purple-50 hover:bg-purple-100 text-purple-700 px-3 py-2 rounded text-center text-sm font-medium">
                            <i class="fas fa-book mr-1"></i> Materials
                        </a>
                        
                        <!-- Add Materials -->
                        <a href="{{ route('teacher.courses.materials.create', $course->id) }}" 
                           class="bg-green-50 hover:bg-green-100 text-green-700 px-3 py-2 rounded text-center text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> Add Material
                        </a>
                        
                        <!-- View Quizzes -->
                        <a href="{{ route('teacher.quizzes.create') }}?course_id={{ $course->id }}" 
                           class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 px-3 py-2 rounded text-center text-sm font-medium">
                            <i class="fas fa-question-circle mr-1"></i> Add Quiz
                        </a>
                    </div>
                    
                    <!-- Additional Links -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('teacher.quizzes.index') }}" 
                           class="block w-full text-center text-sm text-gray-600 hover:text-blue-600">
                            View All Quizzes →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection