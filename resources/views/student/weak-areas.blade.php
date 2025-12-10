{{-- resources/views/student/weak-areas.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weak Areas & Recommendations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .progress-bar { transition: width 0.5s ease-in-out; }
        .topic-card:hover { transform: translateY(-2px); transition: transform 0.2s; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Simple Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg"></div>
                    <span class="text-xl font-bold text-gray-800">Smart Learning</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                    <a href="/" class="text-gray-600 hover:text-blue-600">Courses</a>
                    <a href="{{ route('weak-areas') }}" class="text-blue-600 font-medium">Weak Areas</a>
                    <a href="/" class="text-gray-600 hover:text-blue-600">Schedule</a>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
                        <span class="text-gray-700">Student</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Weak Areas & Recommendations</h1>
            <p class="text-gray-600">Personalized learning insights based on your performance</p>
            
            @if(!$hasQuizData)
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 inline-flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3 text-lg"></i>
                <p class="text-blue-700">Start taking quizzes to see your actual weak areas. Currently showing sample data.</p>
            </div>
            @endif
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                <p class="text-blue-700">{{ session('info') }}</p>
            </div>
        </div>
        @endif

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Left Column: Weak Areas Analysis -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-800">
                                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                                Weak Areas Analysis
                            </h2>
                            <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                                {{ count($weakTopics) }} Topics
                            </span>
                        </div>
                        <p class="text-gray-600 mt-2">Topics where your accuracy is below 60%</p>
                    </div>
                    
                    <!-- Weak Topics List -->
                    <div class="p-6">
                        @if(empty($weakTopics))
                        <div class="text-center py-10">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check text-green-500 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Weak Areas Found</h3>
                            <p class="text-gray-600">Excellent! You're performing well across all topics.</p>
                        </div>
                        @else
                        <div class="space-y-4">
                            @foreach($weakTopics as $topic => $data)
                            @if($data['accuracy'] < 60)
                            <div class="topic-card border border-gray-200 rounded-lg p-5 hover:border-red-200 hover:bg-red-50 transition-all duration-200">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-800">{{ $topic }}</h3>
                                        <p class="text-gray-600 text-sm mt-1">
                                            {{ $data['correct'] }} out of {{ $data['total_questions'] }} questions correct
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block bg-red-100 text-red-800 font-bold px-3 py-1 rounded-full text-lg">
                                            {{ $data['accuracy'] }}%
                                        </span>
                                        <p class="text-xs text-red-600 mt-1 font-medium">Needs Improvement</p>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mb-2">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Accuracy Level</span>
                                        <span>{{ $data['accuracy'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-red-500 h-2.5 rounded-full progress-bar" 
                                             style="width: {{ $data['accuracy'] }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span class="text-red-600 font-medium">Weak Area (< 60%)</span>
                                    <span class="text-green-600 font-medium">Target (≥ 80%)</span>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    
                    <!-- How It Works -->
                    <div class="border-t border-gray-200 p-6 bg-gray-50 rounded-b-xl">
                        <h4 class="font-bold text-gray-700 mb-3">How Weak Areas Are Identified</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-percentage text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Accuracy Threshold</p>
                                    <p class="text-sm text-gray-600">Topics with < 60% accuracy are flagged</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-tag text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Topic Tags</p>
                                    <p class="text-sm text-gray-600">Based on question topic tags from quizzes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Course Recommendations -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <!-- Header -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-800">
                                <i class="fas fa-lightbulb text-green-500 mr-2"></i>
                                Recommended Courses
                            </h2>
                            <span class="bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">
                                {{ $recommendedCourses->count() }} Suggestions
                            </span>
                        </div>
                        <p class="text-gray-600 mt-2">Courses to help you improve your weak areas</p>
                    </div>
                    
                    <!-- Courses List -->
                    <div class="p-6">
                        @if($recommendedCourses->count() > 0)
                        <div class="space-y-5">
                            @foreach($recommendedCourses as $course)
                            <div class="border border-gray-200 rounded-lg p-5 hover:border-green-300 hover:shadow-sm transition-all duration-200">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-800">{{ $course->title }}</h3>
                                        <div class="flex items-center mt-2">
                                            <span class="text-gray-600 text-sm mr-4">
                                                <i class="fas fa-hashtag text-gray-400 mr-1"></i>{{ $course->code }}
                                            </span>
                                            <span class="text-gray-600 text-sm">
                                                <i class="fas fa-folder text-gray-400 mr-1"></i>{{ $course->category }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full">
                                        Recommended
                                    </span>
                                </div>
                                
                                <p class="text-gray-700 text-sm mb-4">{{ Str::limit($course->description, 120) }}</p>
                                
                                @if($course->topic_tags && is_array($course->topic_tags))
                                <div class="mb-5">
                                    <p class="text-sm text-gray-600 mb-2 font-medium">
                                        Covers these topics:
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $tags = $course->topic_tags;
                                            $displayTags = array_slice($tags, 0, 4);
                                        @endphp
                                        
                                        @foreach($displayTags as $tag)
                                        <span class="inline-block bg-gray-100 text-gray-700 text-xs px-3 py-1.5 rounded-full">
                                            {{ $tag }}
                                        </span>
                                        @endforeach
                                        
                                        @if(count($tags) > 4)
                                        <span class="inline-block bg-gray-100 text-gray-500 text-xs px-3 py-1.5 rounded-full">
                                            +{{ count($tags) - 4 }} more
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex justify-between items-center">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        View Course Details
                                    </a>
                                    <form action="{{ route('weak-areas.enroll') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-colors duration-200">
                                            <i class="fas fa-user-plus mr-2"></i>Enroll Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
                                <i class="fas fa-graduation-cap text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-3">No New Recommendations</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                                You're already enrolled in courses that cover your weak areas.
                                Continue learning to discover new recommendations.
                            </p>
                            <a href="#" class="inline-block bg-gray-800 hover:bg-gray-900 text-white font-medium px-6 py-3 rounded-lg">
                                Browse All Courses
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Footer Note -->
                    <div class="border-t border-gray-200 p-6 bg-gray-50 rounded-b-xl">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-sync-alt text-gray-400 mr-3"></i>
                            <p>Recommendations update automatically as you complete more quizzes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-book-open text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Courses Enrolled</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ count($enrolledCourses ?? []) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Weak Areas Identified</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ count(array_filter($weakTopics ?? [], function($topic) { return $topic['accuracy'] < 60; })) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Avg. Topic Accuracy</p>
                        <p class="text-2xl font-bold text-gray-800">
                            @php
                                $accuracies = array_column($weakTopics ?? [], 'accuracy');
                                echo count($accuracies) > 0 ? round(array_sum($accuracies) / count($accuracies)) . '%' : 'N/A';
                            @endphp
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-10 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-blue-600 rounded"></div>
                        <span class="text-lg font-bold text-gray-800">Smart Learning Platform</span>
                    </div>
                    <p class="text-gray-600 text-sm mt-1">Personalized learning experience</p>
                </div>
                <div class="text-gray-500 text-sm">
                    <p>Weak Areas & Recommendations Feature • Module 2 • CSE471 Project</p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for interactivity -->
    <script>
        // Simple progress bar animation
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>
</html>