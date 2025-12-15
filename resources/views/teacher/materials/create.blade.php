@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Add Material to {{ $course->title }}</h1>
        
        <form action="{{ route('teacher.courses.materials.store', $course->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full p-2 border rounded" required>
                    <option value="">Select Type</option>
                    <option value="pdf">PDF</option>
                    <option value="video">Video</option>
                    <option value="image">Image</option>
                    <option value="link">Link</option>
                    <option value="document">Document</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Title</label>
                <input type="text" name="title" class="w-full p-2 border rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">URL</label>
                <input type="url" name="url" class="w-full p-2 border rounded" required 
                       placeholder="https://example.com/resource">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Description (Optional)</label>
                <textarea name="description" rows="3" class="w-full p-2 border rounded"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Add Material
                </button>
                <a href="{{ route('teacher.courses.show', $course->id) }}" 
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection