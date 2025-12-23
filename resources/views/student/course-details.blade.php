<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Course Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Notification context -->
                    @if(session('from_notification'))
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-blue-700">📢 You clicked on a notification about this course</p>
                        </div>
                    @endif
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $course->title }}</h1>
                    
                    <div class="flex items-center mb-6">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <span>Teacher: {{ $course->teacher->name }}</span>
                        </div>
                        <span class="mx-3 text-gray-300">|</span>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <span>Created: {{ $course->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="prose max-w-none mb-8">
                        <h3 class="text-xl font-semibold mb-3">Description</h3>
                        <p class="text-gray-700">{{ $course->description ?? 'No description provided.' }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-2">Course Information</h4>
                            <ul class="space-y-2">
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Category:</span>
                                    <span class="font-medium">{{ $course->category ?? 'General' }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Level:</span>
                                    <span class="font-medium">{{ $course->difficulty ?? 'Beginner' }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium">{{ $course->duration ?? 'Flexible' }}</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-700 mb-2">What you'll get</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Access to course materials</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Quizzes and assessments</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span>Progress tracking</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-2">Quick Stats</h4>
                            <ul class="space-y-2">
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Quizzes:</span>
                                    <span class="font-medium">{{ $course->quizzes->count() }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Materials:</span>
                                    <span class="font-medium">{{ $course->materials->count() }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span class="text-gray-600">Enrolled:</span>
                                    <span class="font-medium">{{ $course->enrollments->count() }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Enroll Button -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-center">
                            <div class="mb-4 sm:mb-0">
                                <p class="text-gray-700">Interested in this course?</p>
                                <p class="text-sm text-gray-500">Enroll to access all content and quizzes</p>
                            </div>
                            
                            @if($isEnrolled)
                                <div class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-medium">
                                    ✓ Already Enrolled
                                </div>
                            @else
                                <form action="{{ route('student.courses.enroll') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <button type="submit" 
                                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition duration-150">
                                        Enroll Now
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>