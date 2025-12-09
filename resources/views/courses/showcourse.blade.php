<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <title>Course Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .course-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .course-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-left-color: #3b82f6;
        }
        .course-code-link {
            color: #3b82f6;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.2s;
        }
        .course-code-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Courses in Database</h1>
            <p class="text-gray-600">Click on any course code to add resources</p>
        </div>

        <!-- Courses List -->
        <div id="coursesContainer" class="space-y-4">
            @php
                $courses = \App\Models\Course::latest()->get();
            @endphp
            
            @if($courses->isEmpty())
                <div class="bg-gray-50 rounded-lg p-8 text-center text-gray-500">
                    <i class="fas fa-book-open text-4xl mb-4 opacity-50"></i>
                    <h3 class="text-xl font-medium mb-2">No courses yet</h3>
                    <p>Create your first course above.</p>
                </div>
            @else
                @foreach($courses as $course)
                    <!-- In your course card -->
                    <div class="course-card bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <!-- Make the whole title or a button clickable -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <!-- Make title clickable -->
                                <a href="{{ route('resources.index', $course->code) }}" 
                                class="text-xl font-bold text-gray-800 hover:text-blue-600 block mb-2">
                                    {{ $course->title }}
                                </a>
                                
                                <div class="flex items-center gap-3 mb-3">
                                    <!-- Course code still goes to add resources -->
                                    <a href="{{ route('resources.create', $course->code) }}" 
                                    class="course-code-link text-lg px-3 py-1 bg-blue-50 hover:bg-blue-100 rounded-lg">
                                        📘 {{ $course->code }}
                                    </a>
                                    
                                    <!-- Add "View Resources" button -->
                                    <a href="{{ route('resources.index', $course->code) }}" 
                                    class="text-sm px-3 py-1 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg flex items-center gap-1">
                                        <i class="fas fa-eye"></i> View Resources
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                        <!-- Resources Preview (if any) -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-paperclip"></i> Resources
                                </h4>
                                <span class="text-xs text-gray-500">
                                    Click course code to add more
                                </span>
                            </div>
                            <!-- You could add existing resources here if you want -->
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Back to Dashboard -->
        <div class="mt-8 text-center">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Add Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>