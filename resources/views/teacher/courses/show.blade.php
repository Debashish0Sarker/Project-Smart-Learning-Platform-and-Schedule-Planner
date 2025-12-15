@extends('layouts.teacher')

@section('title', $course->title . ' - Course Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $course->title }}</h1>
            <div class="flex items-center gap-4 mt-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    {{ $course->code }}
                </span>
                <span class="text-gray-600">{{ ucfirst($course->category) }}</span>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    @if($course->status === 'published') bg-green-100 text-green-800
                    @elseif($course->status === 'draft') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($course->status) }}
                </span>
            </div>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('teacher.courses.edit', $course) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                Edit Course
            </a>
            <a href="{{ route('teacher.courses.index') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium">
                Back to Courses
            </a>
        </div>
    </div>

    <!-- Course Description -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Course Description</h2>
        <p class="text-gray-700">{{ $course->description }}</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-6 border-t">
            <div>
                <p class="text-gray-500 text-sm">Created</p>
                <p class="font-medium">{{ $course->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Last Updated</p>
                <p class="font-medium">{{ $course->updated_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Category</p>
                <p class="font-medium">{{ $course->category }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats & Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Stats -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Course Statistics</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Students</span>
                    <span class="text-2xl font-bold">{{ $course->students->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Quizzes</span>
                    <span class="text-2xl font-bold">{{ $course->quizzes->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Resources</span>
                    <span class="text-2xl font-bold">{{ $course->materials->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('teacher.quizzes.create') }}?course_id={{ $course->id }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Add Quiz</span>
                </a>
                <a href="{{ route('teacher.courses.materials.create', $course->id) }}"
                   class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-paperclip"></i>
                    <span>Add Resource</span>
                </a>
                <a href="{{ route('teacher.quizzes.index') }}" 
                   class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-list"></i>
                    <span>View Quizzes</span>
                </a>
                <a href="{{ route('teacher.courses.materials.index', $course->id) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-lg flex items-center justify-center gap-2">
                    <i class="fas fa-folder-open"></i>
                    <span>View Resources</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Quizzes -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Recent Quizzes</h2>
            <a href="{{ route('teacher.quizzes.create') }}?course_id={{ $course->id }}" 
               class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                + Add Quiz
            </a>
        </div>
        
        @if($course->quizzes->count() > 0)
            <div class="space-y-4">
                @foreach($course->quizzes->take(3) as $quiz)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-medium text-gray-800">{{ $quiz->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $quiz->description }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm px-2 py-1 rounded 
                                @if($quiz->difficulty === 'easy') bg-green-100 text-green-800
                                @elseif($quiz->difficulty === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($quiz->difficulty) }}
                            </span>
                            <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                View →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">No quizzes created yet for this course.</p>
                <a href="{{ route('teacher.quizzes.create') }}?course_id={{ $course->id }}" 
                   class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                    Create your first quiz →
                </a>
            </div>
        @endif
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h2 class="text-xl font-semibold text-red-800 mb-2">Danger Zone</h2>
        <p class="text-red-700 mb-4">Once you delete a course, there is no going back. Please be certain.</p>
        <form action="{{ route('teacher.courses.destroy', $course) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Delete Course
            </button>
        </form>
    </div>
</div>
@endsection