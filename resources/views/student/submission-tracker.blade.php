<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Tracker - Smart Learning Platform</title>
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
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            <a href="{{ route('student.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                                <i class="fas fa-home mr-1"></i>Dashboard
                            </a>
                            <a href="{{ route('student.quizzes.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                                <i class="fas fa-file-alt mr-1"></i>Quizzes
                            </a>
                            <a href="{{ route('student.submission-tracker.index') }}" class="bg-blue-50 text-blue-700 px-3 py-2 rounded-md font-medium">
                                <i class="fas fa-tasks mr-1"></i>Submission Tracker
                            </a>
                            <a href="{{ route('student.courses.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                                <i class="fas fa-book mr-1"></i>Courses
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4">
                        <i class="fas fa-user-circle mr-1"></i>{{ Auth::user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-clipboard-list text-blue-500 mr-3"></i>Submission Tracker
            </h1>
            <p class="text-gray-600 mt-2">Track all your quiz submissions and deadlines</p>
        </div>

        <!-- Status Legend -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border">
            <h3 class="font-medium text-gray-700 mb-3"><i class="fas fa-info-circle mr-2"></i>Status Legend</h3>
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-gray-200 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Not Started</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-blue-100 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Submitted</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-green-100 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Graded</span>
                </div>
                <div class="flex items-center">
                    <span class="w-3 h-3 bg-red-100 rounded-full mr-2"></span>
                    <span class="text-sm text-gray-600">Late</span>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-4">
                <div class="relative">
                    <i class="fas fa-filter absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select id="courseFilter" class="pl-10 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Courses</option>
                        @foreach($quizzes->pluck('course')->unique() as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="relative">
                    <i class="fas fa-flag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <select id="statusFilter" class="pl-10 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="not_started">Not Started</option>
                        <option value="submitted">Submitted</option>
                        <option value="graded">Graded</option>
                        <option value="late">Late</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Quizzes Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($quizzes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($quizzes as $quiz)
                                @php
                                    $response = $quiz->quizResponses->first();
                                    $status = $response ? $response->status : 'not_started';
                                @endphp
                                <tr class="hover:bg-gray-50 quiz-row" 
                                    data-course="{{ $quiz->course_id }}"
                                    data-status="{{ $status }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-file-alt text-blue-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ $quiz->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($quiz->description, 50) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $quiz->course->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $quiz->course->code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($quiz->due_date)
                                            <div class="text-sm text-gray-900 font-medium">
                                                {{ $quiz->due_date->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $quiz->due_date->format('h:i A') }}
                                            </div>
                                            @if($quiz->due_date->isPast())
                                                <div class="text-xs text-red-500 mt-1">
                                                    Due {{ $quiz->due_date->diffForHumans() }}
                                                </div>
                                            @else
                                                <div class="text-xs text-green-500 mt-1">
                                                    Due in {{ $quiz->due_date->diffForHumans() }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-sm">No deadline</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($response)
                                            @if($response->isLate())
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-clock mr-1"></i>Late
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($response->status == 'submitted') bg-blue-100 text-blue-800
                                                    @elseif($response->status == 'graded') bg-green-100 text-green-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    @if($response->status == 'submitted')
                                                        <i class="fas fa-paper-plane mr-1"></i>
                                                    @elseif($response->status == 'graded')
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                    @else
                                                        <i class="fas fa-hourglass-half mr-1"></i>
                                                    @endif
                                                    {{ ucfirst($response->status) }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <i class="fas fa-plus-circle mr-1"></i>Not Started
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($response && $response->score !== null)
                                            <div class="text-sm font-bold text-green-600">
                                                {{ $response->score }}/{{ $quiz->total_points }}
                                            </div>
                                            @if($response->percentage)
                                                <div class="text-xs text-gray-500">
                                                    {{ number_format($response->percentage, 1) }}%
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($response)
                                            <a href="{{ route('student.submission-tracker.show', $response) }}" 
                                               class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                            @if($response->status == 'graded')
                                                <span class="text-green-600">
                                                    <i class="fas fa-check mr-1"></i>Graded
                                                </span>
                                            @endif
                                        @else
                                            @if($quiz->is_published)
                                                <a href="{{ route('student.quizzes.show', $quiz) }}" 
                                                   class="text-green-600 hover:text-green-900 font-medium">
                                                    <i class="fas fa-play-circle mr-1"></i>Start Quiz
                                                </a>
                                            @else
                                                <span class="text-gray-400">
                                                    <i class="fas fa-lock mr-1"></i>Not Available
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <div class="text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No quizzes assigned</h3>
                        <p class="mt-1 text-gray-500">You don't have any quizzes to track yet.</p>
                        <a href="{{ route('student.courses.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                            <i class="fas fa-book mr-1"></i>Browse courses to enroll
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="mt-8 pt-6 border-t text-center text-gray-500 text-sm">
            <p>Smart Learning Platform &copy; {{ date('Y') }} • Submission Tracker</p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseFilter = document.getElementById('courseFilter');
            const statusFilter = document.getElementById('statusFilter');
            const quizRows = document.querySelectorAll('.quiz-row');
            
            function filterRows() {
                const selectedCourse = courseFilter.value;
                const selectedStatus = statusFilter.value;
                
                quizRows.forEach(row => {
                    const courseMatch = !selectedCourse || row.dataset.course === selectedCourse;
                    const statusMatch = !selectedStatus || row.dataset.status === selectedStatus;
                    
                    if (courseMatch && statusMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            if (courseFilter) courseFilter.addEventListener('change', filterRows);
            if (statusFilter) statusFilter.addEventListener('change', filterRows);
        });
    </script>
</body>
</html>