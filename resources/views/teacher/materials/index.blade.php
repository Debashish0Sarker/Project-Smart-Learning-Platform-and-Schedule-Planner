@extends('layouts.teacher')

@section('title', $course->title . ' - Materials')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $course->title }}</h1>
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        {{ $course->code }}
                    </span>
                    <span class="text-gray-600">
                        <i class="fas fa-paperclip mr-1"></i>{{ $materials->count() }} materials
                    </span>
                </div>
                <p class="text-gray-600 mt-4 max-w-3xl">{{ $course->description }}</p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ route('teacher.courses.materials.create', $course->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add Material
                </a>
                <a href="{{ route('teacher.courses.show', $course->id) }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Back to Course
                </a>
            </div>
        </div>
    </div>

    <!-- Materials List -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800">Course Materials</h2>
            <p class="text-gray-600 text-sm mt-1">All uploaded materials for this course</p>
        </div>

        @if($materials->isEmpty())
            <div class="p-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 mb-2">No materials yet</h3>
                <p class="text-gray-500 mb-6">Add your first material to get started</p>
                <a href="{{ route('teacher.courses.materials.create', $course->id) }}" 
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                    <i class="fas fa-plus"></i> Add First Material
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($materials as $material)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $material->title }}</h3>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($material->type === 'pdf') bg-red-100 text-red-800
                                    @elseif($material->type === 'video') bg-blue-100 text-blue-800
                                    @elseif($material->type === 'image') bg-green-100 text-green-800
                                    @elseif($material->type === 'link') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <i class="fas fa-{{ $material->type === 'pdf' ? 'file-pdf' : ($material->type === 'video' ? 'video' : ($material->type === 'image' ? 'image' : 'link')) }} mr-1"></i>
                                    {{ ucfirst($material->type) }}
                                </span>
                            </div>
                            
                            @if($material->description)
                                <p class="text-gray-600 mb-4">{{ $material->description }}</p>
                            @endif
                            
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <i class="far fa-calendar"></i>
                                    Added {{ $material->created_at->format('M d, Y') }}
                                </span>
                                @if($material->url)
                                    <a href="{{ $material->url }}" target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                        <i class="fas fa-external-link-alt"></i> Open Link
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="ml-4 flex items-center gap-3">
                            @if($material->url)
                                <a href="{{ $material->url }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded border border-blue-200 bg-blue-50 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- Upload New Section -->
    <div class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Want to add more materials?</h3>
                <p class="text-gray-600">Upload PDFs, videos, images, or add external links</p>
            </div>
            <a href="{{ route('teacher.courses.materials.create', $course->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Material
            </a>
        </div>
    </div>
</div>
@endsection