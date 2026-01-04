@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="max-w-6xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">Teacher Dashboard</h1>
    
    <!-- Student Search -->
    <div class="mb-6">
        <form method="GET" action="{{ route('teacher.dashboard') }}" class="flex gap-2">
            <input name="student" type="text" placeholder="Search student by name..." value="{{ old('student', $searchQuery ?? '') }}"
                   class="flex-1 border rounded px-3 py-2" />
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
            @if(!empty($searchQuery))
                <a href="{{ route('teacher.dashboard') }}" class="ml-2 px-4 py-2 bg-gray-100 rounded text-sm">Clear</a>
            @endif
        </form>
    </div>

    {{-- Search Results --}}
    @if(isset($searchQuery))
        <div class="bg-white shadow p-6 rounded-xl mb-8">
            <h3 class="text-lg font-semibold mb-4">Search results for "{{ $searchQuery }}"</h3>

            @if($students && $students->count() > 0)
                <div class="space-y-4">
                    @foreach($students as $student)
                        <div class="border rounded p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-medium text-gray-800">{{ $student->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                </div>
                                <div class="text-sm text-gray-600">Student</div>
                            </div>

                            <div class="mt-3">
                                @if($student->quizResponses && $student->quizResponses->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($student->quizResponses as $resp)
                                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                                <div>
                                                    <div class="font-medium text-sm">{{ $resp->quiz->title ?? 'Quiz' }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        @if($resp->submitted_at)
                                                            Submitted: {{ $resp->submitted_at->format('Y-m-d H:i') }}
                                                        @else
                                                            Not Submitted
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    @if($resp->submitted_at)
                                                        <a href="{{ route('teacher.submissions.show', $resp) }}"
                                                           class="text-blue-600 hover:underline text-sm">View Result</a>
                                                    @else
                                                        <span class="text-sm text-gray-500">No Submission</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">No submissions for your courses.</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-500">No students found.</div>
            @endif
        </div>
    @endif

    <!-- Navigation Buttons -->
    <div class="flex gap-4 mb-6">
        <a href="{{ route('teacher.courses.create') }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
            ➕ Create Course
        </a>

        <a href="{{ route('teacher.courses.index') }}"
           class="bg-green-600 text-white px-5 py-2 rounded-lg shadow hover:bg-green-700">
            📚 View Courses
        </a>
        
        <a href="{{ route('teacher.quizzes.create') }}" 
           class="bg-purple-600 text-white px-5 py-2 rounded-lg shadow hover:bg-purple-700">
            📝 Create Quiz
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        @php
            // Calculate totals from courses
            $totalStudents = 0;
            $totalQuizzes = 0;
            foreach ($courses as $course) {
                $totalStudents += $course->students_count ?? 0;
                $totalQuizzes += $course->quizzes_count ?? 0;
            }
        @endphp

        <div class="bg-white shadow p-6 rounded-xl">
            <h3 class="text-gray-500">Total Students</h3>
            <p class="text-4xl font-bold">{{ $totalStudents }}</p>
        </div>

        <div class="bg-white shadow p-6 rounded-xl">
            <h3 class="text-gray-500">Total Courses</h3>
            <p class="text-4xl font-bold">{{ count($courses) }}</p>
        </div>

        <div class="bg-white shadow p-6 rounded-xl">
            <h3 class="text-gray-500">Total Quizzes</h3>
            <p class="text-4xl font-bold">{{ $totalQuizzes }}</p>
        </div>
    </div>

    <!-- Two Column Layout: Charts + Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Charts Section from Debashish -->
    <div>
        <!-- Average Grades Chart -->
        <div class="bg-white shadow p-6 rounded-xl">
            <h3 class="font-semibold mb-4">Average Grades</h3>
            <canvas id="gradeChart"></canvas>
        </div>
    </div>

    <!-- Quick Actions from Your Dashboard -->
    <div class="space-y-6">
        <!-- Quick Actions Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('teacher.quizzes.create') }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold text-center">
                    Create New Quiz
                </a>
                <a href="{{ route('teacher.quizzes.index') }}" 
                   class="block w-full bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold text-center">
                    View All Quizzes
                </a>
                <a href="{{ route('teacher.courses.index') }}" 
                   class="block w-full bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold text-center">
                    View Courses
                </a>
            </div>
        </div>

        <!-- Recent Quizzes -->
        @php
            $recentQuizzes = \App\Models\Quiz::with('course')->latest()->take(3)->get();
        @endphp
        
        @if($recentQuizzes->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Quizzes</h2>
                <div class="space-y-3">
                    @foreach($recentQuizzes as $quiz)
                        <div class="border border-gray-200 rounded p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $quiz->title }}</span>
                                    <p class="text-sm text-gray-500 mt-1">{{ $quiz->course->code ?? 'No Course' }}</p>
                                </div>
                                <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View →
                                </a>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <span>{{ $quiz->questions->count() }} questions</span>
                                <span class="mx-2">•</span>
                                <span>{{ $quiz->difficulty }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

    <!-- Courses List -->
@if($courses->count() > 0)
<div class="bg-white shadow p-6 rounded-xl mb-8">
    <h3 class="text-xl font-semibold mb-4">Your Courses</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($courses as $course)
        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
            <h4 class="font-semibold text-lg text-gray-800">{{ $course->title }}</h4>
            <p class="text-gray-600 text-sm mt-1">{{ $course->code }}</p>
            <div class="flex justify-between text-sm text-gray-600 mt-3">
                <div class="flex items-center">
                    <span class="mr-4">👨‍🎓 {{ $course->students_count ?? 0 }} students</span>
                    <span>📝 {{ $course->quizzes_count ?? 0 }} quizzes</span>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2">
                <a href="{{ route('teacher.courses.show', $course) }}" 
                   class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded text-center text-sm font-medium">
                    View Course
                </a>
                <a href="{{ route('teacher.courses.materials.index', $course->id) }}" 
                   class="bg-purple-50 hover:bg-purple-100 text-purple-700 px-3 py-2 rounded text-center text-sm font-medium">
                    📚 View Materials
                </a>
                <a href="{{ route('teacher.quizzes.create') }}?course={{ $course->id }}" 
                   class="col-span-2 bg-green-50 hover:bg-green-100 text-green-700 px-3 py-2 rounded text-center text-sm font-medium mt-1">
                    ➕ Add Quiz
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
    <div class="bg-white shadow p-6 rounded-xl mb-8">
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg mb-4">You haven't created any courses yet.</p>
            <a href="{{ route('teacher.courses.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                Create Your First Course →
            </a>
        </div>
    </div>
    @endif

</div>

<!-- Add Chart.js if not already in layout -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get actual quiz data for average grades
    @php
        // Get all quizzes for this teacher with their average scores
        $teacherQuizzes = \App\Models\Quiz::where('teacher_id', auth()->id())
            ->with(['quizResponses' => function($query) {
                $query->whereNotNull('percentage');
            }])
            ->get();
        
        $quizLabels = [];
        $quizAverages = [];
        $quizColors = [];
        $borderColors = [];
        
        // Colors for the chart
        $colorPalette = [
            'rgba(59, 130, 246, 0.7)',   // Blue
            'rgba(16, 185, 129, 0.7)',   // Green
            'rgba(139, 92, 246, 0.7)',   // Purple
            'rgba(245, 158, 11, 0.7)',   // Amber
            'rgba(239, 68, 68, 0.7)',    // Red
            'rgba(14, 165, 233, 0.7)',   // Sky
            'rgba(236, 72, 153, 0.7)',   // Pink
        ];
        
        foreach ($teacherQuizzes as $index => $quiz) {
            $quizLabels[] = \Illuminate\Support\Str::limit($quiz->title, 20);
            
            if ($quiz->quizResponses->count() > 0) {
                // Calculate average percentage
                $totalPercentage = $quiz->quizResponses->sum('percentage');
                $average = round($totalPercentage / $quiz->quizResponses->count(), 1);
                $quizAverages[] = $average;
            } else {
                // No submissions yet
                $quizAverages[] = 0;
            }
            
            // Assign color from palette (recycle if more quizzes than colors)
            $color = $colorPalette[$index % count($colorPalette)];
            $quizColors[] = $color;
            $borderColors[] = str_replace('0.7', '1', $color);
        }
        
        // If no quizzes with data, show empty chart
        if (empty($quizLabels)) {
            $quizLabels = ['No quizzes yet'];
            $quizAverages = [0];
            $quizColors = ['rgba(156, 163, 175, 0.7)'];
            $borderColors = ['rgba(156, 163, 175, 1)'];
        }
    @endphp
    
    // Average Grades Chart (Real Data)
    new Chart(document.getElementById('gradeChart'), {
        type: 'bar',
        data: {
            labels: @json($quizLabels),
            datasets: [{
                label: "Average Score (%)",
                data: @json($quizAverages),
                borderWidth: 1,
                backgroundColor: @json($quizColors),
                borderColor: @json($borderColors)
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Score (%)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Quizzes'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Average: ${context.raw}%`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
    {{--
    // Average Grades Chart (Dummy Data)
    new Chart(document.getElementById('gradeChart'), {
        type: 'bar',
        data: {
            labels: ['Quiz 1', 'Quiz 2', 'Quiz 3'],
            datasets: [{
                label: "Average Score",
                data: [78, 85, 91],
                borderWidth: 1,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(139, 92, 246, 0.7)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(139, 92, 246)'
                ]
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    --}}
@endsection