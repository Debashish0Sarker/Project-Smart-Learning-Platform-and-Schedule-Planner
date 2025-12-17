@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Available Courses</h1>

    @if($courses->isEmpty())
        <p class="text-gray-600">No courses available at the moment.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($courses as $course)
                <div class="border rounded-lg p-4 shadow-sm">
                    <h2 class="text-xl font-semibold">{{ $course->title }}</h2>
                    <p class="text-sm text-gray-600">{{ $course->code }} • {{ $course->category }}</p>
                    <p class="mt-2 text-gray-700">{{ Str::limit($course->description, 150) }}</p>

                    <div class="mt-4 flex items-center justify-between">
                        <a href="{{ route('student.courses.details', $course->id) }}" class="text-indigo-600 hover:underline">View details</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            @if(method_exists($courses, 'links'))
                {{ $courses->links() }}
            @endif
        </div>
    @endif
</div>
@endsection
