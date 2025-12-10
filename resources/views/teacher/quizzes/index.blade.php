@extends('layouts.teacher')

@section('title', 'My Quizzes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">My Quizzes</h1>
        <a href="{{ route('teacher.quizzes.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
            Create New Quiz
        </a>
    </div>

    @if($quizzes->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-500">No quizzes created yet.</p>
            <a href="{{ route('teacher.quizzes.create') }}" class="mt-4 inline-block text-blue-600">
                Create your first quiz
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Questions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Difficulty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($quizzes as $quiz)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $quiz->title }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($quiz->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $quiz->course->code }}</td>
                            <td class="px-6 py-4">{{ $quiz->questions->count() }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($quiz->difficulty == 'easy') bg-green-100 text-green-800
                                    @elseif($quiz->difficulty == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($quiz->difficulty) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    View
                                </a>
                                <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="text-green-600 hover:text-green-900 mr-3">
                                    Edit
                                </a>
                                <form action="{{ route('teacher.quizzes.destroy', $quiz) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Delete this quiz?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection