@extends('layouts.app')

@section('title', 'Quiz Submissions - ' . $quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Quiz Submissions</h1>
            <p class="text-gray-600 mt-2">{{ $quiz->title }} - {{ $quiz->course->title }}</p>
        </div>
        <a href="{{ route('teacher.quizzes.show', $quiz) }}" 
           class="text-blue-600 hover:text-blue-800">
            ← Back to Quiz
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm text-gray-500">Total Submissions</p>
                <p class="text-2xl font-bold text-gray-900">{{ $submissions->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm text-gray-500">Submitted</p>
                <p class="text-2xl font-bold text-blue-600">
                    {{ $submissions->where('status', 'submitted')->count() }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm text-gray-500">Graded</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ $submissions->where('status', 'graded')->count() }}
                </p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <p class="text-sm text-gray-500">Late</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ $submissions->where('status', 'late')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($submissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $submission->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $submission->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $submission->getStatusColor() }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->submitted_at)
                                    <div>{{ $submission->submitted_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $submission->submitted_at->format('h:i A') }}</div>
                                @else
                                    <span class="text-gray-400">Not submitted</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->score !== null)
                                    <span class="font-bold text-green-600">
                                        {{ $submission->score }}/{{ $quiz->total_points }}
                                    </span>
                                    @if($submission->percentage)
                                        <div class="text-xs text-gray-500">{{ number_format($submission->percentage, 1) }}%</div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('teacher.submissions.show', $submission) }}" 
                                   class="text-blue-600 hover:text-blue-900 font-medium">
                                    View & Grade
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No submissions yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">No students have submitted this quiz yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection