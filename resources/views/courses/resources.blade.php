<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Resource - {{ $course->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #2563eb; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .back-link { color: #3b82f6; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .upload-hint { color: #6b7280; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div style="margin-bottom: 20px;">
            <h1 style="margin: 0 0 10px 0;">Add Resource to {{ $course->title }}</h1>
            <p style="color: #6b7280; margin: 0 0 10px 0;">Course Code: {{ $course->code }}</p>
            <a href="{{ url('/') }}" class="back-link">← Back to Dashboard</a>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Form -->
        <div class="card">
            <form action="{{ route('resources.store', $course->code) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Resource Type -->
                <div class="form-group">
                    <label for="type">Resource Type *</label>
                    <select name="type" id="type" required>
                        <option value="">Select type</option>
                        <option value="link" {{ old('type') == 'link' ? 'selected' : '' }}>External Link</option>
                        <option value="pdf" {{ old('type') == 'pdf' ? 'selected' : '' }}>PDF Document</option>
                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video File</option>
                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image File</option>
                    </select>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="title">Resource Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           placeholder="e.g., Lecture Slides, Tutorial Video"
                           required>
                </div>

                <!-- URL (for External Links) -->
                <div class="form-group">
                    <label for="url">External URL (for Links)</label>
                    <input type="url" name="url" id="url" value="{{ old('url') }}"
                           placeholder="https://example.com/resource">
                    <p class="upload-hint">For YouTube, Google Drive, websites, etc.</p>
                </div>

                <!-- OR Divider -->
                <div style="text-align: center; margin: 20px 0; position: relative;">
                    <hr style="border: none; border-top: 1px solid #ddd;">
                    <span style="background: white; padding: 0 15px; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); color: #6b7280;">OR</span>
                </div>

                <!-- File Upload (for PDF/Video/Image) -->
                <div class="form-group">
                    <label for="file">Upload File (for PDF/Video/Image)</label>
                    <input type="file" name="file" id="file" 
                           style="border: 2px dashed #3b82f6; padding: 15px; width: 100%; background: #f8fafc;">
                    <p class="upload-hint">
                        <strong>Supported files:</strong><br>
                        • PDF documents (.pdf) - max 10MB<br>
                        • Images (.jpg, .jpeg, .png) - max 5MB<br>
                        • Videos (.mp4, .mov, .avi) - max 20MB
                    </p>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="Brief description of this resource">{{ old('description') }}</textarea>
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="submit">Add Resource</button>
                    <button type="button" onclick="resetForm()" 
                            style="background: #6b7280;">Clear Form</button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div style="background: #dbeafe; border: 1px solid #93c5fd; padding: 15px; border-radius: 6px; margin-top: 20px;">
            <h3 style="margin: 0 0 10px 0; color: #1e40af;">How to add resources:</h3>
            <ul style="margin: 0; padding-left: 20px; color: #1e40af;">
                <li><strong>For URLs:</strong> Choose "External Link" and paste a URL</li>
                <li><strong>For Files:</strong> Choose PDF/Video/Image and upload from computer</li>
                <li><strong>Note:</strong> Use either URL OR File (not both required)</li>
            </ul>
        </div>
    </div>

    <script>
        function resetForm() {
            document.querySelector('form').reset();
            alert('Form cleared!');
        }
    </script>
</body>
</html>