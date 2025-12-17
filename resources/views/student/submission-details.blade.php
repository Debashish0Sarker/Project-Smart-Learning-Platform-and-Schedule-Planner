<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Details - Smart Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('student.dashboard') }}" class="text-xl font-bold text-blue-600">
                            <i class="fas fa-graduation-cap mr-2"></i>Smart Learning
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('student.submission-tracker.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Tracker
                    </a>
                    <span class="text-gray-700">
                        <i class="fas fa-user-circle mr-1"></i>{{ Auth::user()->name }}
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-file-alt text-blue-500 mr-2"></i>{{ $quizResponse->quiz->title }}
                    </h1>
                    <div class="mt-2 flex flex-wrap items-center gap-4">
                        <span class="text-gray-600">
                            <i class="fas fa-book mr-1"></i>Course: <span class="font-medium">{{ $quizResponse->quiz->course->title }}</span>
                        </span>
                        <span class="text-gray-600">
                            <i class="fas fa-hashtag mr-1"></i>Code: <span class="font-medium">{{ $quizResponse->quiz->course->code }}</span>
                        </span>
                        @if($quizResponse->quiz->due_date)
                            <span class="text-gray-600">
                                <i class="fas fa-calendar-alt mr-1"></i>Due: <span class="font-medium">{{ $quizResponse->quiz->due_date->format('M d, Y h:i A') }}</span>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="px-4 py-2 rounded-full text-sm font-medium 
                        @if($quizResponse->status == 'submitted') bg-blue-100 text-blue-800
                        @elseif($quizResponse->status == 'graded') bg-green-100 text-green-800
                        @elseif($quizResponse->status == 'late') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($quizResponse->status == 'submitted')
                            <i class="fas fa-paper-plane mr-1"></i>
                        @elseif($quizResponse->status == 'graded')
                            <i class="fas fa-check-circle mr-1"></i>
                        @elseif($quizResponse->status == 'late')
                            <i class="fas fa-clock mr-1"></i>
                        @else
                            <i class="fas fa-hourglass-half mr-1"></i>
                        @endif
                        {{ ucfirst($quizResponse->status) }}
                        @if($quizResponse->isLate())
                            (Late)
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Score Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Score</h3>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $quizResponse->score ?? 0 }}/{{ $quizResponse->quiz->total_points }}
                        </p>
                        @if($quizResponse->percentage)
                            <p class="text-sm text-gray-500">{{ number_format($quizResponse->percentage, 1) }}%</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submission Time Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Submitted At</h3>
                        <p class="text-lg font-medium text-gray-900">
                            {{ $quizResponse->submitted_at ? $quizResponse->submitted_at->format('M d, Y h:i A') : 'Not submitted' }}
                        </p>
                        @if($quizResponse->submitted_at && $quizResponse->quiz->due_date)
                            @if($quizResponse->isLate())
                                <p class="text-sm text-red-500">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Submitted {{ $quizResponse->submitted_at->diffForHumans($quizResponse->quiz->due_date) }} late
                                </p>
                            @else
                                <p class="text-sm text-green-500">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Submitted {{ $quizResponse->quiz->due_date->diffForHumans($quizResponse->submitted_at, true) }} early
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Duration Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Time Taken</h3>
                        @if($quizResponse->started_at && $quizResponse->submitted_at)
                            @php
                                $duration = $quizResponse->started_at->diff($quizResponse->submitted_at);
                                $minutes = $duration->i;
                                $seconds = $duration->s;
                            @endphp
                            <p class="text-lg font-medium text-gray-900">{{ $minutes }}m {{ $seconds }}s</p>
                        @else
                            <p class="text-lg font-medium text-gray-900">-</p>
                        @endif
                        @if($quizResponse->quiz->duration_minutes)
                            <p class="text-sm text-gray-500">Allowed: {{ $quizResponse->quiz->duration_minutes }} minutes</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Feedback -->
        @if($quizResponse->feedback)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-comment-alt text-blue-500 mr-2"></i>Teacher Feedback
                </h2>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-700 whitespace-pre-line">{{ $quizResponse->feedback }}</p>
                    @if($quizResponse->graded_at)
                        <p class="text-sm text-gray-500 mt-4">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Graded on {{ $quizResponse->graded_at->format('M d, Y h:i A') }}
                        </p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Answers Summary -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-list-ol text-blue-500 mr-2"></i>Quiz Answers Summary
            </h2>
            
            @if($quizResponse->quizAnswers->count() > 0)
                <div class="space-y-6">
                    @foreach($quizResponse->quizAnswers as $index => $answer)
                        <div class="border rounded-lg p-4 {{ $answer->is_correct ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="font-medium text-gray-700 mr-2">
                                            <i class="fas fa-question-circle mr-1"></i>Question {{ $index + 1 }}
                                        </span>
                                        @if($answer->is_correct)
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded">
                                                <i class="fas fa-check mr-1"></i>Correct (+{{ $answer->points_awarded }})
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">
                                                <i class="fas fa-times mr-1"></i>Incorrect (0)
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($answer->question)
                                        <p class="text-gray-700 mb-3">{{ $answer->question->question_text }}</p>
                                    @endif
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 mb-1">
                                                <i class="fas fa-user-edit mr-1"></i>Your Answer
                                            </p>
                                            <p class="text-gray-700 font-medium">{{ $answer->answer_given ?? 'No answer provided' }}</p>
                                        </div>
                                        
                                        @if($answer->question && $answer->question->correct_answers && !$answer->is_correct)
                                            <div>
                                                <p class="text-sm font-medium text-gray-500 mb-1">
                                                    <i class="fas fa-check-circle mr-1"></i>Correct Answer
                                                </p>
                                                <p class="text-green-700 font-medium">{{ $answer->question->correct_answers }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">No detailed answers available for this submission.</p>
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="mt-8 pt-6 border-t text-center text-gray-500 text-sm">
            <p>Smart Learning Platform &copy; {{ date('Y') }} • Submission Details</p>
        </div>
    </div>
</body>
</html>