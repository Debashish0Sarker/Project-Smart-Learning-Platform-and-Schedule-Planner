{{-- resources/views/student/weak-areas-test.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weak Areas Feature Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .progress-bar { transition: width 0.5s ease-in-out; }
        .topic-card:hover { transform: translateY(-2px); transition: transform 0.2s; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">🧠 Weak Areas Feature Test</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                This is a standalone test page for the Weak Areas & Recommendations feature (Module 2, Feature 3).
                No login or dashboard required.
            </p>
            
            <!-- Test Controls -->
            <div class="mt-6 bg-white rounded-xl shadow p-4 inline-block">
                <div class="flex flex-wrap gap-3 items-center">
                    <span class="font-medium text-gray-700">Test Controls:</span>
                    <a href="{{ route('weak-areas.test') }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200">
                        <i class="fas fa-sync mr-2"></i>Refresh
                    </a>
                    <a href="{{ route('weak-areas.clear-enrollments') }}" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200">
                        <i class="fas fa-trash mr-2"></i>Clear Enrollments
                    </a>
                    <span class="text-sm text-gray-500">
                        Student ID: {{ $studentId }} | Enrolled in: {{ count($enrolledCourses) }} courses
                    </span>
                </div>
            </div>
            
            @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif
            
            @if(session('info'))
            <div class="mt-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                {{ session('info') }}
            </div>
            @endif
        </div>

        <!-- Demo Notice -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-flask text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-yellow-800">
                        <strong>Demo Mode:</strong> Showing sample weak topic data. In the full implementation, 
                        weak areas are calculated automatically from quiz performance.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Left Column: Weak Areas -->
            <div>
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                            Weak Topic Analysis
                        </h2>
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ count($weakTopics) }} Topics Analyzed
                        </span>
                    </div>
                    
                    <div class="space-y-5">
                        @foreach($weakTopics as $topic => $data)
                        <div class="topic-card border border-gray-200 rounded-xl p-5 hover:border-blue-200 hover:shadow-md transition-all duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800">{{ $topic }}</h3>
                                    @if($data['is_demo'] ?? false)
                                    <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded mt-1">
                                        <i class="fas fa-vial mr-1"></i>Sample Data
                                    </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if($data['accuracy'] < 60)
                                    <span class="inline-block bg-red-100 text-red-800 font-bold px-3 py-1 rounded-full text-lg">
                                        {{ $data['accuracy'] }}%
                                    </span>
                                    <p class="text-xs text-red-600 mt-1 font-medium">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Needs Improvement
                                    </p>
                                    @else
                                    <span class="inline-block bg-green-100 text-green-800 font-bold px-3 py-1 rounded-full text-lg">
                                        {{ $data['accuracy'] }}%
                                    </span>
                                    <p class="text-xs text-green-600 mt-1 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>Good
                                    </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Performance: {{ $data['correct'] }}/{{ $data['total_questions'] }} correct</span>
                                    <span>{{ $data['accuracy'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="progress-bar h-3 rounded-full 
                                        @if($data['accuracy'] < 60) bg-red-500
                                        @elseif($data['accuracy'] < 80) bg-yellow-500
                                        @else bg-green-500 @endif"
                                        style="width: {{ min($data['accuracy'], 100) }}%">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span class="text-red-600 font-medium">Weak (< 60%)</span>
                                <span class="text-yellow-600">Average (60-80%)</span>
                                <span class="text-green-600 font-medium">Strong (> 80%)</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-gray-100">
                        <h4 class="font-bold text-gray-700 mb-2">How Weak Areas are Determined:</h4>
                        <ul class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                <span>Accuracy < 60% = Weak Area</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-database text-blue-500 mr-2"></i>
                                <span>Based on quiz question tags</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-filter text-green-500 mr-2"></i>
                                <span>Minimum 3 questions per topic</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-chart-bar text-purple-500 mr-2"></i>
                                <span>Updated after each quiz</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recommendations -->
            <div>
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-lightbulb text-green-500 mr-2"></i>
                            Recommended Courses
                        </h2>
                        <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ $recommendedCourses->count() }} Suggestions
                        </span>
                    </div>
                    
                    @if($recommendedCourses->count() > 0)
                    <div class="space-y-5">
                        @foreach($recommendedCourses as $course)
                        <div class="border border-gray-200 rounded-xl p-5 hover:border-green-300 hover:shadow-md transition-all duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800">{{ $course->title }}</h3>
                                    <div class="flex items-center mt-1">
                                        <span class="text-gray-600 text-sm mr-3">
                                            <i class="fas fa-hashtag text-gray-400 mr-1"></i>{{ $course->code }}
                                        </span>
                                        <span class="text-gray-600 text-sm">
                                            <i class="fas fa-folder text-gray-400 mr-1"></i>{{ $course->category }}
                                        </span>
                                    </div>
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">
                                    <i class="fas fa-star mr-1"></i>Recommended
                                </span>
                            </div>
                            
                            <p class="text-gray-700 text-sm mb-4">{{ Str::limit($course->description, 100) }}</p>
                            
                            @if($course->topic_tags && is_array($course->topic_tags))
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2 font-medium">
                                    <i class="fas fa-tags mr-1"></i>Covers these weak topics:
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $tags = $course->topic_tags; // Already an array
                                        $weakTags = [];
                                        foreach ($weakTopics as $topic => $data) {
                                            if ($data['accuracy'] < 60 && in_array($topic, $tags)) {
                                                $weakTags[] = $topic;
                                            }
                                        }
                                    @endphp
                                    
                                    @foreach($weakTags as $tag)
                                    <span class="inline-block bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full">
                                        <i class="fas fa-bullseye mr-1"></i>{{ $tag }}
                                    </span>
                                    @endforeach
                                    
                                    @php
                                        $otherTags = array_diff($tags, array_keys($weakTopics));
                                        $otherTags = array_slice($otherTags, 0, 2);
                                    @endphp
                                    
                                    @foreach($otherTags as $tag)
                                    <span class="inline-block bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">
                                        {{ $tag }}
                                    </span>
                                    @endforeach
                                    
                                    @if(count($tags) > count($weakTags) + count($otherTags))
                                    <span class="inline-block bg-gray-100 text-gray-500 text-xs px-3 py-1 rounded-full">
                                        +{{ count($tags) - count($weakTags) - count($otherTags) }} more
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>Course Details
                                </a>
                                <a href="{{ route('weak-areas.enroll-test', $course->id) }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition-colors">
                                    <i class="fas fa-user-plus mr-1"></i>Enroll (Test)
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-10">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-graduation-cap text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No New Recommendations</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            You're already enrolled in courses covering all your weak areas.
                            Try clearing enrollments to see more recommendations.
                        </p>
                        <div class="space-x-3">
                            <a href="{{ route('weak-areas.clear-enrollments') }}" 
                               class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-5 py-2 rounded-lg">
                                <i class="fas fa-trash mr-2"></i>Clear Enrollments
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Debug Info (Collapsible) -->
                    <details class="mt-8 pt-6 border-t border-gray-200">
                        <summary class="text-sm text-gray-500 cursor-pointer hover:text-gray-700">
                            <i class="fas fa-bug mr-1"></i>Debug Information
                        </summary>
                        <div class="mt-3 bg-gray-50 p-4 rounded-lg text-xs">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="font-medium mb-1">Weak Topics (< 60%):</p>
                                    <ul class="text-gray-600">
                                        @foreach($weakTopics as $topic => $data)
                                            @if($data['accuracy'] < 60)
                                            <li>{{ $topic }} ({{ $data['accuracy'] }}%)</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <p class="font-medium mb-1">Enrolled Courses:</p>
                                    <ul class="text-gray-600">
                                        @foreach($allCourses as $course)
                                            @if(in_array($course->id, $enrolledCourses))
                                            <li>#{{ $course->id }}: {{ $course->title }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <p class="mt-3 text-gray-500">
                                Logic: Recommends courses with matching tags, excluding enrolled courses.
                            </p>
                        </div>
                    </details>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-10 text-center text-gray-500 text-sm">
            <p>Weak Areas Feature • Module 2, Feature 3 • Smart Learning Platform</p>
            <p class="mt-1">This test page works independently of the main dashboard and authentication system.</p>
        </div>
    </div>
</body>
</html>