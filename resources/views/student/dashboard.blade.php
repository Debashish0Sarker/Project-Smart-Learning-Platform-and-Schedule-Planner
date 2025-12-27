<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Motivational Tip Section -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-start">
                        <div class="mr-3 mt-1">
                            <span class="text-2xl">💡</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-1">Daily Motivation</h3>
                            <p class="text-gray-700 italic">"{{ $motivationalTip }}"</p>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Quizzes & Actions -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Active Quizzes -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">📝 Active Quizzes</h3>
                            @if($upcomingQuizzes->count() > 0)
                                <div class="space-y-4">
                                    @foreach($upcomingQuizzes as $quiz)
                                        <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium text-gray-800">{{ $quiz->title }}</h4>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        Course: {{ $quiz->course->title ?? 'Unknown' }}
                                                    </p>
                                                </div>
                                                <a href="{{ route('student.quizzes.show', $quiz) }}" 
                                                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                                    Take Quiz
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No active quizzes at the moment.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    
                </div>

                <!-- Right Column: Schedule & Available Courses -->
                <div class="space-y-6">
                    <!-- Static Schedule -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">📅 Today's Schedule</h3>
                            <div class="space-y-3">
                                @foreach($staticSchedule as $item)
                                    <div class="flex items-start p-3 bg-gray-50 rounded border border-gray-200">
                                        <div class="mr-3 mt-1">
                                            <span class="text-gray-500">🕐</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $item['time'] }}</p>
                                            <p class="text-sm text-gray-600">{{ $item['subject'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $item['room'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-4 italic">*Sample schedule for demonstration</p>
                        </div>
                    </div>

                    <!-- Available Courses -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">📚 Available Courses</h3>
                            <div class="space-y-3">
                                @foreach($availableCourses as $course)
                                    <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-gray-800">{{ Str::limit($course->title, 30) }}</h4>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    {{ $course->category }} • By {{ $course->teacher->name ?? 'Teacher' }}
                                                </p>
                                            </div>
                                            <a href="{{ route('student.course.materials', $course) }}" 
                                               class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">
                                                Materials
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if(method_exists($availableCourses, 'total') ? $availableCourses->total() > 5 : $availableCourses->count() > 5)
                                    <div class="text-center mt-4">
                                        <a href="{{ route('student.courses.index') }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                           View all {{ method_exists($availableCourses, 'total') ? $availableCourses->total() : $availableCourses->count() }} courses →
                                        </a>
                                    </div>
                                @endif
                                
                                @if($availableCourses->isEmpty())
                                    <p class="text-gray-500 text-center py-4">No courses available at the moment.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        function refreshTip() {
            fetch('{{ route("student.motivational-tip.refresh") }}')
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Find and update the tip text
                        const tipElement = document.querySelector('.bg-gradient-to-r p.text-gray-700');
                        if(tipElement) {
                            tipElement.textContent = '"' + data.tip + '"';
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    @endsection
</x-app-layout>