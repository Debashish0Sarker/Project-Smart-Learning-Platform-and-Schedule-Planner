<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <!-- Use Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen p-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Create New Course</h1>
            <p class="text-gray-600">Fill in the details below to create a new course</p>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                ← Back to Home
            </a>
        </div>

        <!-- Laravel Session Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-6">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm ml-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Laravel Form -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <form action="{{ route('teacher.courses.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Title Field -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Course Title *
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title"
                        value="{{ old('title') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('title') border-red-500 @enderror"
                        placeholder="e.g., Introduction to Web Development">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code Field -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                        Course Code *
                    </label>
                    <input 
                        type="text" 
                        id="code" 
                        name="code"
                        value="{{ old('code') }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('code') border-red-500 @enderror"
                        placeholder="e.g., CS101, WEBDEV-101">
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Field -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                        Category *
                    </label>
                    <select 
                        id="category" 
                        name="category"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('category') border-red-500 @enderror">
                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select a category</option>
                        <option value="programming" {{ old('category') == 'programming' ? 'selected' : '' }}>Programming</option>
                        <option value="design" {{ old('category') == 'design' ? 'selected' : '' }}>Design</option>
                        <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Business</option>
                        <option value="science" {{ old('category') == 'science' ? 'selected' : '' }}>Science</option>
                        <option value="mathematics" {{ old('category') == 'mathematics' ? 'selected' : '' }}>Mathematics</option>
                        <option value="language" {{ old('category') == 'language' ? 'selected' : '' }}>Language</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Field -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description *
                    </label>
                    <textarea 
                        id="description" 
                        name="description"
                        rows="4"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('description') border-red-500 @enderror"
                        placeholder="Describe the course content, objectives, and requirements...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-4">
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        Create Course
                    </button>
                    <button 
                        type="button" 
                        onclick="resetForm()"
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                        Reset Form
                    </button>
                </div>
            </form>
        </div>

        <!-- Display Courses from Database -->
        <div class="mt-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Courses in Database</h2>
            <div id="coursesContainer" class="space-y-4">
                @php
                    $courses = \App\Models\Course::latest()->take(5)->get();
                @endphp
                
                @if($courses->isEmpty())
                    <div class="bg-gray-50 rounded-lg p-6 text-center text-gray-500">
                        No courses in database yet. Create your first course above.
                    </div>
                @else
                    @foreach($courses as $course)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800">{{ $course->title }}</h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-sm text-gray-600 font-medium">{{ $course->code }}</span>
                                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                            {{ ucfirst($course->category) }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500">
                                    {{ $course->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mt-2">{{ $course->description }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <script>
        // Simple form reset
        function resetForm() {
            document.querySelector('form').reset();
            
            // Show reset message
            const messageContainer = document.createElement('div');
            messageContainer.className = 'bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4 mb-6';
            messageContainer.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Form has been reset.
                </div>
            `;
            
            // Insert after header
            const header = document.querySelector('.mb-8');
            header.parentNode.insertBefore(messageContainer, header.nextSibling);
            
            // Remove message after 3 seconds
            setTimeout(() => {
                messageContainer.remove();
            }, 3000);
        }
    </script>
</body>
</html>