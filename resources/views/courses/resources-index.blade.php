<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources - {{ $course->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .resource-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .resource-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .pdf { border-left-color: #ef4444; }
        .video { border-left-color: #3b82f6; }
        .image { border-left-color: #10b981; }
        .link { border-left-color: #f59e0b; }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-pdf { background-color: #fee2e2; color: #dc2626; }
        .badge-video { background-color: #dbeafe; color: #1d4ed8; }
        .badge-image { background-color: #d1fae5; color: #065f46; }
        .badge-link { background-color: #fef3c7; color: #d97706; }
        .file-preview {
            border: 2px dashed #e5e7eb;
            transition: all 0.3s ease;
        }
        .file-preview:hover {
            border-color: #3b82f6;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
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
                            <i class="fas fa-tag mr-1"></i>{{ ucfirst($course->category) }}
                        </span>
                        <span class="text-gray-600">
                            <i class="far fa-file-alt mr-1"></i>{{ $resources->count() }} resources
                        </span>
                    </div>
                    <p class="text-gray-600 mt-4 max-w-3xl">{{ $course->description }}</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('resources.create', $course->code) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Resource
                    </a>
                    <a href="{{ route('courses.showcourse') }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> All Courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Resources</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $resources->count() }}</p>
                    </div>
                    <i class="fas fa-paperclip text-blue-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">PDF Files</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $resources->where('type', 'pdf')->count() }}</p>
                    </div>
                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Videos</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $resources->where('type', 'video')->count() }}</p>
                    </div>
                    <i class="fas fa-video text-blue-500 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">External Links</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $resources->where('type', 'link')->count() }}</p>
                    </div>
                    <i class="fas fa-link text-yellow-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Resources List -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800">Course Resources</h2>
                <p class="text-gray-600 text-sm mt-1">All uploaded materials for this course</p>
            </div>

            @if($resources->isEmpty())
                <div class="p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700 mb-2">No resources yet</h3>
                    <p class="text-gray-500 mb-6">Add your first resource to get started</p>
                    <a href="{{ route('resources.create', $course->code) }}" 
                       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                        <i class="fas fa-plus"></i> Add First Resource
                    </a>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($resources as $resource)
                    <div class="resource-card {{ $resource->type }} p-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $resource->title }}</h3>
                                    <span class="badge badge-{{ $resource->type }}">
                                        @switch($resource->type)
                                            @case('pdf')
                                                <i class="fas fa-file-pdf mr-1"></i> PDF
                                                @break
                                            @case('video')
                                                <i class="fas fa-video mr-1"></i> Video
                                                @break
                                            @case('image')
                                                <i class="fas fa-image mr-1"></i> Image
                                                @break
                                            @case('link')
                                                <i class="fas fa-link mr-1"></i> Link
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                                
                                @if($resource->description)
                                    <p class="text-gray-600 mb-4">{{ $resource->description }}</p>
                                @endif
                                
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <i class="far fa-calendar"></i>
                                        Added {{ $resource->created_at->format('M d, Y') }}
                                    </span>
                                    @if($resource->url)
                                        <a href="{{ $resource->url }}" target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                            <i class="fas fa-external-link-alt"></i> Open Link
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="ml-4 flex items-center gap-3">
                                @if($resource->file_path)
                                    <a href="{{ Storage::url($resource->file_path) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded border border-blue-200 bg-blue-50 text-sm font-medium">
                                        <i class="fas fa-download mr-1"></i> Download
                                    </a>
                                @endif
                                
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- File preview or URL -->
                        <div class="mt-4">
                            @if($resource->url)
                                <div class="file-preview rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-link text-yellow-500 text-xl"></i>
                                            <div>
                                                <p class="font-medium text-gray-800">External Link</p>
                                                <p class="text-sm text-gray-600 truncate max-w-md">{{ $resource->url }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ $resource->url }}" target="_blank" 
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            Visit →
                                        </a>
                                    </div>
                                </div>
                            @elseif($resource->file_path)
                                <div class="file-preview rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            @switch($resource->type)
                                                @case('pdf')
                                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                                    @break
                                                @case('video')
                                                    <i class="fas fa-video text-blue-500 text-xl"></i>
                                                    @break
                                                @case('image')
                                                    <i class="fas fa-image text-green-500 text-xl"></i>
                                                    @break
                                            @endswitch
                                            <div>
                                                <p class="font-medium text-gray-800">{{ basename($resource->file_path) }}</p>
                                                <p class="text-sm text-gray-600">{{ $resource->type }} file</p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($resource->file_path) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800 font-medium">
                                            Open →
                                        </a>
                                    </div>
                                </div>
                            @endif
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Want to add more resources?</h3>
                    <p class="text-gray-600">Upload PDFs, videos, images, or add external links</p>
                </div>
                <a href="{{ route('resources.create', $course->code) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center gap-2">
                    <i class="fas fa-plus"></i> Add New Resource
                </a>
            </div>
        </div>
    </div>

    <script>
        // Simple file type icons
        document.addEventListener('DOMContentLoaded', function() {
            // Add download confirmation
            document.querySelectorAll('a[href*="download"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!confirm('Download this file?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>