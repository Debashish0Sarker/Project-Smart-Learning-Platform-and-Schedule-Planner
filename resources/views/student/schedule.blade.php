{{-- resources/views/student/schedule.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Schedule</h1>
    <p class="text-gray-600">This is a placeholder schedule page. You can replace it with the real schedule UI.</p>

    <div class="mt-6 bg-white p-6 rounded-lg shadow-sm">
        <p class="text-sm text-gray-700">No scheduled items yet for demo users.</p>
        <a href="{{ route('student.weak-areas') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded">Back to Weak Areas</a>
    </div>
</div>
@endsection
