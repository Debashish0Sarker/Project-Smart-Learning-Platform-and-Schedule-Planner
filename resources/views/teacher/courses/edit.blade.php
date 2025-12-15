@extends('layouts.teacher')

@section('title', 'Edit Course - ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit Course</h1>
        <p class="text-gray-600 mt-2">Update course information</p>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('teacher.courses.update', $course) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Course Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Course Title *
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $course->title) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Code and Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            Course Code *
                        </label>
                        <input type="text" 
                               id="code" 
                               name="code" 
                               value="{{ old('code', $course->code) }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category *
                        </label>
                        <select id="category" 
                                name="category" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Category</option>
                            <option value="Mathematics" {{ old('category', $course->category) == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                            <option value="Science" {{ old('category', $course->category) == 'Science' ? 'selected' : '' }}>Science</option>
                            <option value="Programming" {{ old('category', $course->category) == 'Programming' ? 'selected' : '' }}>Programming</option>
                            <option value="Languages" {{ old('category', $course->category) == 'Languages' ? 'selected' : '' }}>Languages</option>
                            <option value="Business" {{ old('category', $course->category) == 'Business' ? 'selected' : '' }}>Business</option>
                            <option value="Arts" {{ old('category', $course->category) == 'Arts' ? 'selected' : '' }}>Arts</option>
                            <option value="Other" {{ old('category', $course->category) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status *
                    </label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $course->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description *
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('teacher.courses.show', $course) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update Course
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection