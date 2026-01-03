
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $quiz->title }} - Submission Successful</title>
    
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

        .pending-container {
            max-width: 1200px;
            margin: auto;
        }

        .success-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            transition: var(--transition-default);
        }

        .success-card:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .answer-item {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 2px solid #e5e7eb;
            margin-bottom: 1.5rem;
            transition: var(--transition-default);
        }

        .answer-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
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

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            animation: scaleIn 0.6s ease-out;
        }

        .checkmark {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: block;
            stroke-width: 4;
            stroke: #ffffff;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #10b981;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 4;
            stroke-miterlimit: 10;
            stroke: #ffffff;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 40px #10b981;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
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

        .pulse-ring {
            position: absolute;
            width: 100px;
            height: 100px;
            border: 4px solid rgba(16, 185, 129, 0.3);
            border-radius: 50%;
            animation: pulse 2s ease-out infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.8);
                opacity: 1;
            }
            100% {
                transform: scale(1.2);
                opacity: 0;
            }
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
        <div class="pending-container px-4">
            <!-- Success Card -->
            <div class="success-card animate-fadeInUp">
                <div class="text-center mb-8">
                    <div class="relative inline-block mb-6">
                        <div class="pulse-ring"></div>
                        <div class="success-icon">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h1 class="text-3xl font-bold text-gray-900 mb-3">Quiz Submission Successful!</h1>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-500 text-xl mr-2"></i>
                            <p class="text-xl text-green-600 font-medium">{{ $message }}</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 max-w-2xl mx-auto mb-8">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-clipboard-check text-blue-600 text-xl"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="text-xl font-bold text-gray-900">{{ $quiz->title }}</h3>
                                <p class="text-gray-600">Your quiz has been submitted successfully</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <div class="p-3 bg-white rounded-lg border border-gray-200">
                                <div class="text-sm text-gray-500 mb-1">Submitted At</div>
                                <div class="font-medium text-gray-900">
                                    <i class="far fa-clock mr-2 text-blue-500"></i>
                                    {{ now()->format('F j, Y \a\t g:i A') }}
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Submitted Answers Section -->
                <div class="border-t border-gray-200 pt-8">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-list-check text-indigo-600"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Submitted Answers</h2>
                            <p class="text-gray-600 text-sm">Review your responses below</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($details as $detail)
                            <div class="answer-item 
                                {{ $detail['status'] === 'correct' ? 'border-green-200 bg-green-50/30' : 
                                   ($detail['status'] === 'wrong' ? 'border-red-200 bg-red-50/30' : 
                                   'border-yellow-200 bg-yellow-50/30') }}">
                                
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-start">
                                        <span class="text-lg font-bold 
                                            {{ $detail['status'] === 'correct' ? 'text-green-700' : 
                                               ($detail['status'] === 'wrong' ? 'text-red-700' : 
                                               'text-yellow-700') }} mr-3 mt-1">
                                            Q{{ $loop->iteration }}.
                                        </span>
                                        <div>
                                            <div class="font-medium text-gray-900">Question ID: {{ $detail['question_id'] }}</div>
                                            @if(isset($detail['points']))
                                                <div class="text-sm text-gray-500 mt-1">
                                                    <i class="fas fa-coins mr-1"></i>
                                                    {{ $detail['points'] }} point(s)
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <span class="badge 
                                            {{ $detail['status'] === 'correct' ? 'badge-success' : 
                                               ($detail['status'] === 'wrong' ? 'badge-danger' : 
                                               'badge-warning') }}">
                                            <i class="fas 
                                                {{ $detail['status'] === 'correct' ? 'fa-check mr-2' : 
                                                   ($detail['status'] === 'wrong' ? 'fa-times mr-2' : 
                                                   'fa-exclamation mr-2') }}"></i>
                                            {{ ucfirst($detail['status']) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="p-4 bg-white rounded-lg border border-gray-200">
                                        <div class="flex items-center mb-3">
                                            <i class="fas fa-user-edit text-gray-500 mr-2"></i>
                                            <div class="text-sm font-medium text-gray-700">Your Selection:</div>
                                        </div>
                                        <div class="mt-2">
                                            @if(is_array($detail['selected']))
                                                @if(count($detail['selected']) > 0)
                                                    <div class="space-y-2">
                                                        @foreach($detail['selected'] as $selected)
                                                            <div class="px-3 py-2 bg-gray-100 rounded-lg text-gray-800 flex items-center">
                                                                <i class="fas fa-circle text-xs mr-2 text-gray-500"></i>
                                                                {{ $selected }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="px-3 py-3 bg-gray-100 rounded-lg text-gray-500 italic flex items-center">
                                                        <i class="fas fa-ban mr-2"></i>
                                                        No answer selected
                                                    </div>
                                                @endif
                                            @else
                                                <div class="px-3 py-2 bg-gray-100 rounded-lg text-gray-800 flex items-center">
                                                    <i class="fas fa-circle text-xs mr-2 text-gray-500"></i>
                                                    {{ $detail['selected'] ?? 'No answer' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="p-4 bg-white rounded-lg border border-gray-200">
                                        <div class="flex items-center mb-3">
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            <div class="text-sm font-medium text-gray-700">Correct Answer:</div>
                                        </div>
                                        <div class="mt-2">
                                            @if(is_array($detail['correct']))
                                                <div class="space-y-2">
                                                    @foreach($detail['correct'] as $correct)
                                                        <div class="px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-green-800 font-medium flex items-center">
                                                            <i class="fas fa-check text-xs mr-2 text-green-500"></i>
                                                            {{ $correct }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-green-800 font-medium flex items-center">
                                                    <i class="fas fa-check text-xs mr-2 text-green-500"></i>
                                                    {{ $detail['correct'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-500">
                                            <i class="fas 
                                                {{ $detail['status'] === 'correct' ? 'fa-thumbs-up text-green-500' : 
                                                   ($detail['status'] === 'wrong' ? 'fa-thumbs-down text-red-500' : 
                                                   'fa-lightbulb text-yellow-500') }} mr-2"></i>
                                            {{ $detail['status'] === 'correct' ? 'Well done!' : 
                                               ($detail['status'] === 'wrong' ? 'Better luck next time!' : 
                                               'Result Pending!') }}
                                        </div>
                                        
                                        <div class="text-lg 
                                            {{ $detail['status'] === 'correct' ? 'text-green-600' : 
                                               ($detail['status'] === 'wrong' ? 'text-red-600' : 
                                               'text-yellow-600') }}">
                                            @if($detail['status'] === 'correct')
                                                <i class="fas fa-check-circle"></i>
                                            @elseif($detail['status'] === 'wrong')
                                                <i class="fas fa-times-circle"></i>
                                            @else
                                                <i class="fas fa-exclamation-circle"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            <p class="text-sm">Your results will be available soon after evaluation</p>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('student.dashboard') }}" class="primary-button">
                                <i class="fas fa-home mr-2"></i>
                                Back to Dashboard
                            </a>
                            
                            <a href="{{ route('student.practice-quiz.create') }}" class="secondary-button">
                                <i class="fas fa-dumbbell mr-2"></i>
                                Practice Quiz
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Motivational Tip -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 rounded-r-lg p-6 animate-fadeInUp">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-quote-left text-indigo-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Great Job!
                    </h3>
                    <p class="mt-2 text-gray-700 text-lg">
                        You've successfully completed the quiz! Keep up the momentum and continue learning.
                    </p>
                    <div class="mt-3 flex items-center text-sm text-gray-600">
                        <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                        Tip: Reviewing your answers helps reinforce learning
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to answer items on scroll
            const answerItems = document.querySelectorAll('.answer-item');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            answerItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'all 0.6s ease-out';
                observer.observe(item);
            });

            // Add slight delay for checkmark animation
            setTimeout(() => {
                document.querySelector('.success-icon').style.animation = 'scaleIn 0.6s ease-out, pulse 2s ease-out infinite 0.6s';
            }, 100);
        });
    </script>
</body>
</html>