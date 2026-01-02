
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $course->title }} - Course Materials</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --card-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            --transition-default: all 0.3s ease;
        }

        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .dashboard-header {
            background: var(--primary-gradient);
            padding: 2rem 0;
            margin-bottom: 2rem;
            color: white;
        }

        .materials-shell {
            max-width: 1200px;
            margin: auto;
        }

        .course-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            transition: var(--transition-default);
        }

        .course-card:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .material-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 2px solid #e5e7eb;
            transition: var(--transition-default);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .material-card:hover {
            transform: translateY(-4px);
            border-color: #c7d2fe;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        }

        .material-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
            transition: var(--transition-default);
        }

        .material-card:hover .material-icon {
            transform: scale(1.1);
        }

        .action-btn {
            border-radius: 0.75rem;
            font-weight: 600;
            transition: var(--transition-default);
            padding: 0.75rem 1rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            text-align: center;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .badge-published {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-draft {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .type-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .type-video {
            background: #fee2e2;
            color: #991b1b;
        }

        .type-pdf {
            background: #dbeafe;
            color: #1e40af;
        }

        .type-image {
            background: #d1fae5;
            color: #065f46;
        }

        .type-other {
            background: #f3f4f6;
            color: #4b5563;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            margin: 0 0.125rem;
        }

        .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            border-bottom: 2px solid white;
        }

        .primary-button {
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: var(--transition-default);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .primary-button:hover {
            background: linear-gradient(135deg, var(--primary-hover), #7c3aed);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .secondary-button {
            background: #f1f5f9;
            color: #475569;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: var(--transition-default);
            border: 1px solid #e2e8f0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .secondary-button:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }

        .empty-state {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 1.5rem;
            padding: 4rem 2rem;
            text-align: center;
        }

        .progress-bar {
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #34d399);
            border-radius: 3px;
            transition: width 1s ease;
        }

        .motivation-card {
            background: linear-gradient(135deg, #e0e7ff, #ede9fe);
            border-radius: 1.5rem;
            padding: 1.5rem;
            border: none;
            margin-bottom: 1.5rem;
            border-left: 6px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Smartlearn</h1>
                    <p class="text-white/90">Your personalized learning dashboard</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Navigation Links -->
                    <div class="hidden sm:flex space-x-2">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            {{ __('Dashboard') }}
                        </a>

                        <a href="/student/weak-areas" class="nav-link {{ request()->is('student/weak-areas*') ? 'active' : '' }}">
                            {{ __('Weak Areas') }}
                        </a>

                        <a href="{{ route('student.practice-quiz.create') }}" class="nav-link {{ request()->is('student/practice-quiz*') ? 'active' : '' }}">
                            {{ __('Practice Quiz') }}
                        </a>

                        <a href="/student/submission-tracker" class="nav-link {{ request()->is('student/submission-tracker*') ? 'active' : '' }}">
                            {{ __('Submission Tracker') }}
                        </a>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="secondary-button">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="materials-shell px-4">
            <!-- Course Header Card -->
            <div class="course-card animate-fadeInUp">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-book text-indigo-600 text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ $course->title }}</h1>
                                <div class="flex items-center mt-2 text-gray-600">
                                    <i class="fas fa-hashtag mr-2"></i>
                                    <span class="mr-4">{{ $course->code }}</span>
                                    <i class="fas fa-tag mr-2"></i>
                                    <span>{{ $course->category }}</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-700 text-lg mt-4 leading-relaxed">
                            {{ $course->description }}
                        </p>

                        <div class="flex flex-wrap gap-3 mt-6">
                            <span class="badge {{ $course->status == 'published' ? 'badge-published' : 'badge-draft' }}">
                                <i class="fas 
                                    {{ $course->status == 'published' ? 'fa-check-circle mr-2' : 'fa-pencil-alt mr-2' }}"></i>
                                {{ ucfirst($course->status) }}
                            </span>
                            
                            @if($course->teacher)
                                <span class="badge bg-purple-100 text-purple-800 border-purple-200">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                                    Instructor: {{ $course->teacher->name }}
                                </span>
                            @endif
                            
                            <span class="badge bg-blue-100 text-blue-800 border-blue-200">
                                <i class="fas fa-layer-group mr-2"></i>
                                {{ $materials->count() }} Materials
                            </span>
                        </div>
                    </div>

                    <div class="md:text-right">
                        <a href="{{ route('student.dashboard') }}" 
                           class="primary-button">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Dashboard
                        </a>
                        
                        <div class="mt-4 text-sm text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            Last updated: {{ now()->format('M j, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materials Section -->
            <div class="course-card animate-fadeInUp" style="animation-delay: 0.2s">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-folder-open text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Course Materials</h2>
                            <p class="text-gray-600">Resources and learning materials for this course</p>
                        </div>
                    </div>
                    
                    <div class="text-lg font-semibold text-indigo-600">
                        <i class="fas fa-box-open mr-2"></i>
                        {{ $materials->count() }} materials available
                    </div>
                </div>

                @if($materials->isEmpty())
                    <div class="empty-state">
                        <div class="text-6xl mb-6">📭</div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">
                            No Materials Available Yet
                        </h3>
                        <p class="text-gray-600 max-w-md mx-auto mb-8">
                            Course materials will appear here once they are published by your instructor.
                            Check back soon!
                        </p>
                        <a href="{{ route('student.dashboard') }}" 
                           class="primary-button inline-flex">
                            <i class="fas fa-home mr-2"></i>
                            Return to Dashboard
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($materials as $material)
                            <div class="material-card animate-fadeInUp" 
                                 style="animation-delay: {{ $loop->index * 0.1 }}s">
                                
                                <!-- Icon -->
                                <div class="mb-4">
                                    @if($material->type == 'video')
                                        <div class="material-icon bg-red-100 text-red-700">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                    @elseif($material->type == 'pdf')
                                        <div class="material-icon bg-blue-100 text-blue-700">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                    @elseif($material->type == 'image')
                                        <div class="material-icon bg-green-100 text-green-700">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @else
                                        <div class="material-icon bg-gray-100 text-gray-700">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Title -->
                                <h3 class="font-bold text-xl text-gray-900 mb-3">
                                    {{ $material->title }}
                                </h3>

                                <!-- Description -->
                                @if($material->description)
                                    <p class="text-gray-600 mb-4 flex-1">
                                        {{ Str::limit($material->description, 100) }}
                                    </p>
                                @endif

                                <!-- Type Badge -->
                                <div class="mb-6">
                                    <span class="type-badge 
                                        {{ $material->type == 'video' ? 'type-video' :
                                           ($material->type == 'pdf' ? 'type-pdf' :
                                           ($material->type == 'image' ? 'type-image' :
                                           'type-other')) }}">
                                        <i class="fas 
                                            {{ $material->type == 'video' ? 'fa-video mr-1' :
                                               ($material->type == 'pdf' ? 'fa-file-pdf mr-1' :
                                               ($material->type == 'image' ? 'fa-image mr-1' :
                                               'fa-file mr-1')) }}"></i>
                                        {{ strtoupper($material->type) }}
                                    </span>
                                    
                                    @if($material->file_size)
                                        <span class="ml-2 text-sm text-gray-500">
                                            <i class="fas fa-weight-hanging mr-1"></i>
                                            {{ $material->file_size }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-auto space-y-3">
                                    <!-- View Button -->
                                    @if($material->type == 'video')
                                        <a href="{{ route('student.material.show', $material) }}"
                                           class="block w-full text-center px-4 py-3 bg-red-600 text-white action-btn hover:bg-red-700"
                                           onclick="markAsViewed({{ $material->id }})">
                                            <i class="fas fa-play mr-2"></i>
                                            Watch Video
                                        </a>
                                    @elseif($material->type == 'pdf')
                                        <a href="{{ route('student.material.show', $material) }}"
                                           class="block w-full text-center px-4 py-3 bg-blue-600 text-white action-btn hover:bg-blue-700"
                                           onclick="markAsViewed({{ $material->id }})">
                                            <i class="fas fa-eye mr-2"></i>
                                            View PDF
                                        </a>
                                    @else
                                        <a href="{{ route('student.material.show', $material) }}"
                                           class="block w-full text-center px-4 py-3 bg-indigo-600 text-white action-btn hover:bg-indigo-700"
                                           onclick="markAsViewed({{ $material->id }})">
                                            <i class="fas fa-external-link-alt mr-2"></i>
                                            View Material
                                        </a>
                                    @endif

                                    <!-- Download Button -->
                                    <a href="{{ route('student.material.download', $material) }}"
                                       class="block w-full text-center px-4 py-3 bg-green-600 text-white action-btn hover:bg-green-700">
                                        <i class="fas fa-download mr-2"></i>
                                        Download File
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Progress & Stats -->
            @if(!$materials->isEmpty())
                <div class="course-card animate-fadeInUp" style="animation-delay: 0.4s">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Learning Progress</h2>
                            <p class="text-gray-600">Track your course material completion</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 bg-blue-50 rounded-xl">
                            <div class="text-sm text-blue-600 font-medium mb-2">
                                <i class="fas fa-book-open mr-2"></i>Materials Viewed
                            </div>
                            <div class="text-3xl font-bold text-gray-900">0 / {{ $materials->count() }}</div>
                            <div class="progress-bar mt-2">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-purple-50 rounded-xl">
                            <div class="text-sm text-purple-600 font-medium mb-2">
                                <i class="fas fa-clock mr-2"></i>Time Spent
                            </div>
                            <div class="text-3xl font-bold text-gray-900">0 min</div>
                            <div class="text-sm text-gray-500 mt-2">Start learning to track time</div>
                        </div>
                        
                        <div class="p-4 bg-green-50 rounded-xl">
                            <div class="text-sm text-green-600 font-medium mb-2">
                                <i class="fas fa-check-circle mr-2"></i>Completion
                            </div>
                            <div class="text-3xl font-bold text-gray-900">0%</div>
                            <div class="text-sm text-gray-500 mt-2">Complete all materials to finish</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Motivational Tip -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 rounded-r-lg p-6 animate-fadeInUp">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-lightbulb text-indigo-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Daily Motivation
                    </h3>
                    <p class="mt-2 text-gray-700 italic text-lg motivational-tip-text">
                        @if(isset($motivationalTip) && $motivationalTip)
                            "{{ $motivationalTip }}"
                        @else
                            "The beautiful thing about learning is that no one can take it away from you."
                        @endif
                    </p>
                    <button onclick="refreshTip()" class="mt-3 text-sm text-indigo-600 hover:text-indigo-800 flex items-center font-medium">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Get New Tip
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to material cards on scroll
            const cards = document.querySelectorAll('.material-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease-out';
                observer.observe(card);
            });

            // Simulate progress update
            setTimeout(() => {
                const progressFill = document.querySelector('.progress-fill');
                if (progressFill) {
                    progressFill.style.width = '25%';
                }
            }, 1000);
        });

        function markAsViewed(materialId) {
            fetch(`/student/materials/${materialId}/mark-viewed`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Show success notification
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fadeInUp';
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            <span>Material marked as viewed!</span>
                        </div>
                    `;
                    document.body.appendChild(notification);
                    
                    // Update progress
                    const progressFill = document.querySelector('.progress-fill');
                    const viewedCount = document.querySelector('.text-3xl.font-bold.text-gray-900');
                    if (progressFill && viewedCount) {
                        const current = parseInt(viewedCount.textContent.split(' / ')[0]);
                        const total = parseInt(viewedCount.textContent.split(' / ')[1]);
                        if (current < total) {
                            const newCount = current + 1;
                            viewedCount.textContent = `${newCount} / ${total}`;
                            const newPercentage = (newCount / total) * 100;
                            progressFill.style.width = `${newPercentage}%`;
                        }
                    }
                    
                    setTimeout(() => notification.remove(), 3000);
                }
            })
            .catch(err => {
                console.error('Error marking as viewed:', err);
            });
        }

        function refreshTip() {
            const button = event.target;
            const originalContent = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            button.disabled = true;

            fetch('{{ route("student.motivational-tip.refresh") }}')
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Update the tip text
                        const tipElement = document.querySelector('.motivational-tip-text');
                        if(tipElement) {
                            tipElement.textContent = '"' + data.tip + '"';
                            
                            // Add fade animation
                            tipElement.style.opacity = '0';
                            tipElement.style.transform = 'translateY(10px)';
                            
                            setTimeout(() => {
                                tipElement.style.transition = 'all 0.3s ease';
                                tipElement.style.opacity = '1';
                                tipElement.style.transform = 'translateY(0)';
                            }, 50);
                        }
                        
                        // Restore button
                        button.innerHTML = '<i class="fas fa-check mr-2"></i>Updated!';
                        
                        // Revert button after 2 seconds
                        setTimeout(() => {
                            button.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Get New Tip';
                            button.disabled = false;
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Error';
                    setTimeout(() => {
                        button.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Get New Tip';
                        button.disabled = false;
                    }, 2000);
                });
        }
    </script>
</body>
</html>