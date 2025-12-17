<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Quiz Result
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold">{{ $quiz->title }} - Result</h2>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <div class="text-sm text-gray-600">Score</div>
                                <div class="text-3xl font-bold">{{ $score }} / {{ $total }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-600">Percentage</div>
                                <div class="text-3xl font-bold {{ $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-600">Status</div>
                                <div class="text-2xl font-bold {{ $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $percentage >= 70 ? 'Pass' : ($percentage >= 50 ? 'Average' : 'Fail') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="font-bold mt-8 mb-4 text-lg">Question Details:</h3>
                    <ul class="space-y-4">
                        @foreach($details as $detail)
                            <li class="p-4 border border-gray-200 rounded-lg {{ $detail['status'] === 'correct' ? 'bg-green-50' : ($detail['status'] === 'wrong' ? 'bg-red-50' : 'bg-yellow-50') }}">
                                <div class="font-medium">Question {{ $detail['question_id'] }}</div>
                                
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-sm text-gray-600">Your Answer:</div>
                                        <div class="font-medium">
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
                                    </div>
                                    
                                    <div>
                                        <div class="text-sm text-gray-600">Correct Answer:</div>
                                        <div class="font-medium">
                                            @if(is_array($detail['correct']))
                                                {{ implode(', ', $detail['correct']) }}
                                            @else
                                                {{ $detail['correct'] }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3 flex justify-between items-center">
                                    <div>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            {{ $detail['status'] === 'correct' ? 'bg-green-100 text-green-800' : 
                                               ($detail['status'] === 'wrong' ? 'bg-red-100 text-red-800' : 
                                               'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($detail['status']) }}
                                        </span>
                                        @if(isset($detail['points']))
                                            <span class="ml-2 text-gray-600">({{ $detail['points'] }} point(s))</span>
                                        @endif
                                    </div>
                                    
                                    <div class="text-lg">
                                        @if($detail['status'] === 'correct')
                                            ✓
                                        @elseif($detail['status'] === 'wrong')
                                            ✗
                                        @else
                                            ?
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8">
                        <a href="{{ route('student.dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('student.partials.motivational-tip')
</x-app-layout>