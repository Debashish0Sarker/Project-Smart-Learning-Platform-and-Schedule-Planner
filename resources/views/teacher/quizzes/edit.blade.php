@extends('layouts.teacher')

@section('title', 'Edit Quiz')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Quiz: {{ $quiz->title }}</h1>
    
    <form action="{{ route('teacher.quizzes.update', $quiz) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
            <!-- Quiz Information -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Quiz Information</h2>
                
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Quiz Title *
                        </label>
                        <input type="text" id="title" name="title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md"
                               value="{{ old('title', $quiz->title) }}" required>
                    </div>
                    
                    <!-- Difficulty -->
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                            Difficulty Level *
                        </label>
                        <select id="difficulty" name="difficulty" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                            <option value="easy" {{ old('difficulty', $quiz->difficulty) == 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="medium" {{ old('difficulty', $quiz->difficulty) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="hard" {{ old('difficulty', $quiz->difficulty) == 'hard' ? 'selected' : '' }}>Hard</option>
                        </select>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md">{{ old('description', $quiz->description) }}</textarea>
                    </div>
                </div>
            </div>
            
            <!-- Questions Preview -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Questions ({{ $quiz->questions->count() }})</h2>
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    @foreach($quiz->questions as $index => $question)
                        <div class="py-2 border-b border-gray-200 last:border-b-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium">Q{{ $index + 1 }}:</span>
                                    <span class="ml-2">{{ Str::limit($question->question_text, 50) }}</span>
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ $question->topic_tag }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $question->points }} pts</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('teacher.quizzes.show', $quiz) }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md">
                    Update Quiz
                </button>
            </div>
        </div>
    </form>
</div>
@endsection