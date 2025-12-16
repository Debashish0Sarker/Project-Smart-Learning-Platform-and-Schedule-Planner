@extends('layouts.teacher')

@section('title', 'Add Material - ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Add Material to {{ $course->title }}</h1>
            <p class="text-gray-600">Course Code: <span class="font-medium">{{ $course->code }}</span></p>
        </div>
        
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="font-medium text-red-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    Please fix the following errors:
                </h3>
                <ul class="text-red-600 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-start gap-2">
                            <i class="fas fa-chevron-right mt-1 text-xs"></i>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('teacher.courses.materials.store', $course->id) }}" method="POST" enctype="multipart/form-data" id="materialForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Resource Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Resource Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="typeSelect" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select Type</option>
                        <option value="pdf">PDF Document</option>
                        <option value="video">Video</option>
                        <option value="image">Image</option>
                        <option value="link">External Link</option>
                        <option value="document">Document</option>
                    </select>
                </div>
                
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="e.g., Lecture Slides, Assignment PDF" required>
                </div>
                
                <!-- URL Section (initially hidden, shown for link type) -->
                <div id="urlSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        External URL <span class="text-red-500">*</span>
                    </label>
                    <input type="url" name="url" id="urlInput" value="{{ old('url') }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="https://example.com/resource">
                    <p class="mt-2 text-sm text-gray-500 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i>
                        For YouTube videos, Google Drive links, websites, etc.
                    </p>
                </div>
                
                <!-- File Upload Section (shown for non-link types) -->
                <div id="fileSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload File <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <label for="fileInput" class="cursor-pointer">
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <span id="fileLabel" class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                            Click to upload a file
                                        </span>
                                    </div>
                                    <p id="fileTypeHint" class="text-xs text-gray-500">
                                        Select a file type first
                                    </p>
                                    <p id="fileName" class="text-sm font-medium text-green-600 mt-2"></p>
                                </div>
                            </div>
                        </label>
                        <!-- Hidden file input - accept attribute will be updated dynamically -->
                        <input id="fileInput" name="file" type="file" class="sr-only">
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea name="description" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Brief description of this resource...">{{ old('description') }}</textarea>
                </div>
                
                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i>
                        Add Material
                    </button>
                    <a href="{{ route('teacher.courses.materials.index', $course->id) }}" 
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                </div>
            </div>
        </form>
        
        <!-- Help Text -->
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-medium text-blue-800 mb-2 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                How to add materials:
            </h3>
            <ul class="text-blue-700 text-sm space-y-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-link mt-0.5"></i>
                    <div>
                        <strong>For External Links:</strong> Select "External Link" type and enter the URL
                        <p class="text-blue-600 text-xs mt-1">Example: YouTube videos, Google Drive links, websites</p>
                    </div>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-file-upload mt-0.5"></i>
                    <div>
                        <strong>For File Uploads:</strong> Select PDF/Video/Image type and upload file
                        <p class="text-blue-600 text-xs mt-1">Supported formats will be shown based on selected type</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('typeSelect');
        const urlSection = document.getElementById('urlSection');
        const fileSection = document.getElementById('fileSection');
        const urlInput = document.getElementById('urlInput');
        const fileInput = document.getElementById('fileInput');
        const fileLabel = document.getElementById('fileLabel');
        const fileName = document.getElementById('fileName');
        const fileTypeHint = document.getElementById('fileTypeHint');
        
        // File type configurations
        const fileTypeConfigs = {
            'pdf': {
                accept: '.pdf',
                hint: 'PDF files only (.pdf) up to 20MB'
            },
            'video': {
                accept: '.mp4,.mov,.avi,.mkv,.webm',
                hint: 'Video files: MP4, MOV, AVI, MKV, WebM up to 50MB'
            },
            'image': {
                accept: '.jpg,.jpeg,.png,.gif,.bmp,.webp',
                hint: 'Image files: JPG, PNG, GIF, BMP, WebP up to 10MB'
            },
            'document': {
                accept: '.doc,.docx,.txt,.rtf,.odt',
                hint: 'Document files: DOC, DOCX, TXT, RTF, ODT up to 10MB'
            }
        };
        
        // Show/hide sections based on type
        function updateSections() {
            const type = typeSelect.value;
            
            if (type === 'link') {
                urlSection.classList.remove('hidden');
                fileSection.classList.add('hidden');
                urlInput.required = true;
                fileInput.required = false;
                fileInput.value = ''; // Clear file input
                fileName.textContent = '';
            } else if (type && type !== 'link' && fileTypeConfigs[type]) {
                urlSection.classList.add('hidden');
                fileSection.classList.remove('hidden');
                urlInput.required = false;
                fileInput.required = true;
                urlInput.value = ''; // Clear URL if not link type
                
                // Update file input accept attribute based on type
                const config = fileTypeConfigs[type];
                fileInput.accept = config.accept;
                fileTypeHint.textContent = config.hint;
                
                // Clear previous file selection
                fileInput.value = '';
                fileName.textContent = '';
                fileLabel.textContent = 'Click to upload a file';
            } else {
                urlSection.classList.add('hidden');
                fileSection.classList.add('hidden');
                urlInput.required = false;
                fileInput.required = false;
            }
        }
        
        // File input change handler
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                const type = typeSelect.value;
                const config = fileTypeConfigs[type];
                
                // Validate file extension
                const fileExt = '.' + file.name.split('.').pop().toLowerCase();
                const acceptedExtensions = config.accept.split(',').map(ext => ext.trim());
                
                if (!acceptedExtensions.includes(fileExt)) {
                    alert(`Invalid file type. Please select a ${type} file. Allowed: ${acceptedExtensions.join(', ')}`);
                    fileInput.value = '';
                    fileName.textContent = '';
                    fileLabel.textContent = 'Click to upload a file';
                    return;
                }
                
                // Validate file size
                const maxSize = type === 'video' ? 50 * 1024 * 1024 : 
                               type === 'pdf' ? 20 * 1024 * 1024 : 
                               10 * 1024 * 1024;
                
                if (file.size > maxSize) {
                    alert(`File too large. Maximum size: ${maxSize / (1024 * 1024)}MB`);
                    fileInput.value = '';
                    fileName.textContent = '';
                    fileLabel.textContent = 'Click to upload a file';
                    return;
                }
                
                fileLabel.textContent = 'Change file';
                fileName.textContent = `Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            } else {
                fileLabel.textContent = 'Click to upload a file';
                fileName.textContent = '';
            }
        });
        
        // Type select change handler
        typeSelect.addEventListener('change', updateSections);
        
        // Initialize on page load
        updateSections();
        
        // Form validation
        document.getElementById('materialForm').addEventListener('submit', function(e) {
            const type = typeSelect.value;
            
            if (type === 'link') {
                if (!urlInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter a URL for external links');
                    urlInput.focus();
                    return false;
                }
                
                // Basic URL validation
                try {
                    new URL(urlInput.value);
                } catch (_) {
                    e.preventDefault();
                    alert('Please enter a valid URL (e.g., https://example.com)');
                    urlInput.focus();
                    return false;
                }
            }
            
            if (type && type !== 'link') {
                if (!fileInput.files.length) {
                    e.preventDefault();
                    alert('Please upload a file');
                    return false;
                }
            }
        });
    });
</script>
@endsection