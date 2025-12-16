<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Quiz Submission Successful
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2>{{ $quiz->title }}</h2>
                    <p class="text-lg text-blue-600">{{ $message }}</p>

                    <h4 class="font-bold mt-6 mb-3">Submitted Answers:</h4>
                    <ul class="space-y-4">
                        @foreach($details as $detail)
                            <li class="p-4 border border-gray-200 rounded-lg">
                                <div class="font-medium">Question ID {{ $detail['question_id'] }}:</div>
                                <div class="mt-1">
                                    <span class="font-medium">Selected:</span>
                                    @if(is_array($detail['selected']))
                                        @if(count($detail['selected']) > 0)
                                            {{ implode(', ', $detail['selected']) }}
                                        @else
                                            <span class="text-gray-500">No answer selected</span>
                                        @endif
                                    @else
                                        {{ $detail['selected'] ?? 'No answer' }}
                                    @endif
                                </div>
                                <div class="mt-1">
                                    <span class="font-medium">Correct:</span>
                                    @if(is_array($detail['correct']))
                                        {{ implode(', ', $detail['correct']) }}
                                    @else
                                        {{ $detail['correct'] }}
                                    @endif
                                </div>
                                <div class="mt-1">
                                    <span class="font-medium">Status:</span>
                                    <span class="{{ $detail['status'] === 'correct' ? 'text-green-600' : ($detail['status'] === 'wrong' ? 'text-red-600' : 'text-yellow-600') }}">
                                        {{ ucfirst($detail['status']) }}
                                    </span>
                                    @if(isset($detail['points']))
                                        <span class="ml-2 text-gray-600">({{ $detail['points'] }} point(s))</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8">
                        <a href="{{ route('student.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>