<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Dashboard - Student Portal</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
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

        .dashboard-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: var(--transition-default);
            border: 1px solid #e5e7eb;
        }

        .dashboard-card:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            margin-right: 1rem;
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
        }

        .secondary-button:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        .schedule-item {
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            margin-bottom: 0.75rem;
            transition: var(--transition-default);
            background: #f8fafc;
        }

        .schedule-item:hover {
            background: #f1f5f9;
            border-color: #c7d2fe;
        }

        .course-card {
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            margin-bottom: 0.75rem;
            transition: var(--transition-default);
            background: white;
        }

        .course-card:hover {
            border-color: #c7d2fe;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .motivation-card {
            background: linear-gradient(135deg, #dbeafe 0%, #ede9fe 100%);
            border-radius: 1.5rem;
            padding: 1.5rem;
            border: none;
            margin-bottom: 1.5rem;
            border-left: 6px solid var(--primary-color);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }

        .refresh-button {
            background: transparent;
            border: 2px solid #6366f1;
            color: #6366f1;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: var(--transition-default);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .refresh-button:hover {
            background: #6366f1;
            color: white;
        }

        /* Navigation Links Styling */
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
    </style>
</head>
<body>
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome Back, Student</h1>
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
        <!-- Motivation Card -->
        <div class="motivation-card animate-fadeInUp">
            <div class="flex justify-between items-start">
                <div class="flex items-start">
                    <div class="mr-4">
                        <div class="card-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Daily Motivation</h3>
                        <p class="text-gray-700 italic text-lg">"{{ $motivationalTip }}"</p>
                    </div>
                </div>
                <button onclick="refreshTip()" class="refresh-button">
                    <i class="fas fa-sync-alt mr-2"></i>New Tip
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Active Quizzes -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Active Quizzes Card -->
                <div class="dashboard-card animate-fadeInUp">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Active Quizzes</h3>
                            <p class="text-gray-600 text-sm">Quizzes available for you to take</p>
                        </div>
                    </div>

                    @if($upcomingQuizzes->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingQuizzes as $quiz)
                                <div class="course-card">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-800 text-lg mb-1">{{ $quiz->title }}</h4>
                                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                                <i class="fas fa-book mr-2"></i>
                                                <span>Course: {{ $quiz->course->title ?? 'Unknown' }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('student.quizzes.show', $quiz) }}" 
                                           class="primary-button px-6 py-2">
                                            <i class="fas fa-play mr-2"></i>Take Quiz
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <p class="text-gray-500 text-lg mb-4">No active quizzes at the moment</p>
                            <p class="text-gray-400">Check back later for new assignments</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions Card (if you want to add it back) -->
                <!-- <div class="dashboard-card animate-fadeInUp">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Quick Actions</h3>
                            <p class="text-gray-600 text-sm">Frequently used actions</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Add quick action buttons here -->
                    <!-- </div>
                </div> -->
            </div>

            <!-- Right Column: Schedule & Courses -->
            <div class="space-y-6">
                <!-- Schedule Card -->
                <div class="dashboard-card animate-fadeInUp">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Today's Schedule</h3>
                            <p class="text-gray-600 text-sm">Your classes for today</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($staticSchedule as $item)
                            <div class="schedule-item">
                                <div class="flex items-start">
                                    <div class="mr-4 mt-1 text-gray-500">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-1">
                                            <p class="font-bold text-gray-800">{{ $item['time'] }}</p>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                {{ $item['room'] }}
                                            </span>
                                        </div>
                                        <p class="text-gray-700">{{ $item['subject'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 italic">
                            <i class="fas fa-info-circle mr-1"></i>
                            Sample schedule for demonstration
                        </p>
                    </div>
                </div>

                <!-- Available Courses Card -->
                <div class="dashboard-card animate-fadeInUp">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Available Courses</h3>
                            <p class="text-gray-600 text-sm">Courses you're enrolled in</p>
                        </div>
                    </div>

                    @if($availableCourses->count() > 0)
                        <div class="space-y-3">
                            @foreach($availableCourses as $course)
                                <div class="course-card">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-800 mb-1">{{ Str::limit($course->title, 30) }}</h4>
                                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                                <i class="fas fa-tag mr-2"></i>
                                                <span>{{ $course->category }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                                <span>{{ $course->teacher->name ?? 'Teacher' }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('student.course.materials', $course) }}" 
                                           class="secondary-button px-4 py-2">
                                            <i class="fas fa-folder-open mr-2"></i>Materials
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(method_exists($availableCourses, 'total') ? $availableCourses->total() > 5 : $availableCourses->count() > 5)
                            <div class="mt-6 text-center">
                                <a href="{{ route('student.courses.index') }}" 
                                   class="primary-button px-8 py-3">
                                   <i class="fas fa-eye mr-2"></i>
                                   View all {{ method_exists($availableCourses, 'total') ? $availableCourses->total() : $availableCourses->count() }} courses
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="fas fa-book"></i>
                            </div>
                            <p class="text-gray-500 text-lg">No courses available</p>
                            <p class="text-gray-400 text-sm mt-2">Contact your administrator to enroll</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function refreshTip() {
            const button = event.target.closest('.refresh-button');
            const originalContent = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            button.disabled = true;

            fetch('{{ route("student.motivational-tip.refresh") }}')
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Find and update the tip text
                        const tipElement = document.querySelector('.motivation-card .text-gray-700');
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
                            button.innerHTML = originalContent;
                            button.disabled = false;
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Error';
                    setTimeout(() => {
                        button.innerHTML = originalContent;
                        button.disabled = false;
                    }, 2000);
                });
        }

        // Add fade-in animations to cards on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dashboard-card, .motivation-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            cards.forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</body>
</html>