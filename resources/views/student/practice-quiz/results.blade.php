{{-- resources/views/student/practice-quiz/results.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <header class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Quiz Results</h1>
            </header>

            <!-- Score Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    @php
                        $percentage = $results['percentage'];
                        $scoreColor = $percentage >= 80 ? 'text-green-600' : 
                                    ($percentage >= 60 ? 'text-yellow-600' : 'text-red-600');
                        $bgColor = $percentage >= 80 ? 'bg-green-100' : 
                                  ($percentage >= 60 ? 'bg-yellow-100' : 'bg-red-100');
                    @endphp
                    
                    <div class="inline-block {{ $bgColor }} {{ $scoreColor }} rounded-full p-8 mb-4">
                        <div class="text-5xl font-bold">{{ number_format($percentage, 1) }}%</div>
                        <div class="text-lg mt-2">{{ $results['score'] }}/{{ $results['total'] }} correct</div>
                    </div>
                    
                    <h2 class="text-2xl font-bold mb-2">
                        @if($percentage >= 80) Excellent! 🎉
                        @elseif($percentage >= 60) Good Job! 👍
                        @else Keep Practicing! 📚
                        @endif
                    </h2>
                    
                    @if(isset($params['category']))
                        <p class="text-gray-600 mb-4">
                            Topic: <span class="font-semibold">{{ $params['category'] }}</span> | 
                            Difficulty: <span class="font-weight: 600;">{{ $params['difficulty'] ?? 'any' }}</span>
                        </p>
                    @endif
                    
                    <div class="flex justify-center space-x-4 mt-6">
                        <a href="{{ route('student.practice-quiz.create') }}" 
                           class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2"
                           style="color: #1f2937 !important; background-color: #a5b4fc !important; border: 2px solid #4f46e5;">
                            <span class="flex items-center" style="color: inherit;">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #3730a3;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span style="font-weight: 700; color: #3730a3;">Take Another Quiz</span>
                            </span>
                        </a>
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex justify-center py-2 px-6 border shadow-sm text-sm font-medium rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2"
                           style="color: #1f2937 !important; background-color: #f3f4f6 !important; border: 2px solid #9ca3af;">
                            <span class="flex items-center" style="color: inherit;">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #4b5563;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span style="font-weight: 700; color: #4b5563;">Back to Dashboard</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Detailed Review -->
            <h3 class="text-xl font-bold mb-4 text-gray-800">Question Review</h3>
            
            @foreach($results['results'] as $index => $result)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 {{ $result['is_correct'] ? 'border-green-200' : 'border-red-200' }} border">
                    <div class="p-6 bg-white">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-medium text-gray-900">Question {{ $index + 1 }}</h4>
                            <span class="{{ $result['is_correct'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} 
                                        px-3 py-1 rounded-full text-sm font-medium">
                                {{ $result['is_correct'] ? 'Correct' : 'Incorrect' }}
                            </span>
                        </div>
                        
                        <p class="mb-4 text-gray-700">{{ $result['question'] }}</p>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">Your Answer:</p>
                                <div class="p-3 bg-gray-50 rounded-md">
                                    @if(is_array($result['user_answer']))
                                        @if(count($result['user_answer']) > 0)
                                            <ul class="list-disc pl-5">
                                                @foreach($result['user_answer'] as $answer)
                                                    <li class="text-gray-700">{{ $answer }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-gray-500">No answer given</p>
                                        @endif
                                    @else
                                        <p class="text-gray-700">{{ $result['user_answer'] ?? 'No answer given' }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">Correct Answer{{ count($result['correct_answers']) > 1 ? 's' : '' }}:</p>
                                <div class="p-3 bg-green-50 rounded-md">
                                    @if(count($result['correct_answers']) > 1)
                                        <ul class="list-disc pl-5">
                                            @foreach($result['correct_answers'] as $answer)
                                                <li class="text-green-700">{{ $answer }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-green-700">{{ $result['correct_answers'][0] ?? 'No answer' }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Action Button -->
            <div class="text-center mt-8">
                <a href="{{ route('student.practice-quiz.create') }}" 
                   class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-medium rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2"
                   style="color: #1f2937 !important; background-color: #a5b4fc !important; border: 2px solid #4f46e5;">
                    <span class="flex items-center" style="color: inherit;">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #3730a3;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span style="font-weight: 700; color: #3730a3;">Practice Again</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>