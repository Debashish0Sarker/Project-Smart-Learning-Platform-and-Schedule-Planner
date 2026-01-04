<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $quiz->title }} - Quiz Result</title>
    
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
            --danger-color: #ef4444;
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

        .result-container {
            max-width: 1200px;
            margin: auto;
        }

        .result-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            transition: var(--transition-default);
        }

        .result-card:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .score-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0 auto;
            position: relative;
        }

        .score-circle::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 8px solid;
            opacity: 0.2;
        }

        .question-item {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 2px solid #e5e7eb;
            margin-bottom: 1.5rem;
            transition: var(--transition-default);
        }

        .question-item:hover {
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

        .badge-correct {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-wrong {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .badge-partial {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
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

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .animate-pulse-slow {
            animation: pulse 2s ease-in-out infinite;
        }

        .status-pass {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .status-average {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .status-fail {
            background: linear-gradient(135deg, #ef4444, #f87171);
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
        <div class="result-container px-4">
            <!-- Result Summary Card -->
            <div class="result-card animate-fadeInUp">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-8">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-trophy text-indigo-600 text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ $quiz->title }}</h1>
                                <p class="text-gray-600 mt-1">Quiz Result Summary</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="text-sm text-gray-600 mb-2 flex items-center justify-center">
                                    <i class="fas fa-star mr-2"></i>Score
                                </div>
                                <div class="text-4xl font-bold text-gray-900">{{ $score }} / {{ $total }}</div>
                                <div class="text-sm text-gray-500 mt-2">Total Points</div>
                            </div>
                            
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="text-sm text-gray-600 mb-2 flex items-center justify-center">
                                    <i class="fas fa-chart-pie mr-2"></i>Percentage
                                </div>
                                <div class="text-4xl font-bold 
                                    {{ $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                                <div class="text-sm text-gray-500 mt-2">Overall Performance</div>
                            </div>
                            
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <div class="text-sm text-gray-600 mb-2 flex items-center justify-center">
                                    <i class="fas fa-flag mr-2"></i>Status
                                </div>
                                <div class="text-3xl font-bold 
                                    {{ $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $percentage >= 70 ? 'Excellent!' : ($percentage >= 50 ? 'Good Job' : 'Needs Improvement') }}
                                </div>
                                <div class="text-sm mt-2">
                                    <span class="px-3 py-1 rounded-full 
                                        {{ $percentage >= 70 ? 'bg-green-100 text-green-800' : 
                                           ($percentage >= 50 ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ $percentage >= 70 ? 'Pass' : ($percentage >= 50 ? 'Average' : 'Fail') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Score Circle -->
                    <div class="relative">
                        <div class="score-circle 
                            {{ $percentage >= 70 ? 'status-pass' : ($percentage >= 50 ? 'status-average' : 'status-fail') }} 
                            animate-pulse-slow">
                            <div class="text-center">
                                <div class="text-white text-5xl font-bold">{{ number_format($percentage, 0) }}%</div>
                                <div class="text-white/80 text-sm mt-2">SCORE</div>
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <div class="text-lg font-semibold text-gray-700">
                                {{ $score }} out of {{ $total }} correct
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question Details Card -->
            <div class="result-card animate-fadeInUp" style="animation-delay: 0.2s">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-question-circle text-blue-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Question Details</h2>
                    <span class="ml-4 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                        {{ count($details) }} Questions
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($details as $detail)
                        <div class="question-item 
                            {{ $detail['status'] === 'correct' ? 'border-green-200 bg-green-50/50' : 
                               ($detail['status'] === 'wrong' ? 'border-red-200 bg-red-50/50' : 
                               'border-yellow-200 bg-yellow-50/50') }}">
                            
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-start">
                                    <span class="text-lg font-bold 
                                        {{ $detail['status'] === 'correct' ? 'text-green-700' : 
                                           ($detail['status'] === 'wrong' ? 'text-red-700' : 
                                           'text-yellow-700') }} mr-3 mt-1">
                                        Q{{ $loop->iteration }}.
                                    </span>
                                    <div>
                                        <div class="font-medium text-gray-900">Question {{ $detail['question_id'] }}</div>
                                        @if(isset($detail['points']))
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-coins mr-1"></i>
                                                {{ $detail['points'] }} point(s)
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <span class="badge 
                                        {{ $detail['status'] === 'correct' ? 'badge-correct' : 
                                           ($detail['status'] === 'wrong' ? 'badge-wrong' : 
                                           'badge-partial') }}">
                                        <i class="fas 
                                            {{ $detail['status'] === 'correct' ? 'fa-check mr-2' : 
                                               ($detail['status'] === 'wrong' ? 'fa-times mr-2' : 
                                               'fa-exclamation mr-2') }}"></i>
                                        {{ ucfirst($detail['status']) }}
                                    </span>
                                    
                                    <div class="ml-4 text-2xl 
                                        {{ $detail['status'] === 'correct' ? 'text-green-600' : 
                                           ($detail['status'] === 'wrong' ? 'text-red-600' : 
                                           'text-yellow-600') }}">
                                        @if($detail['status'] === 'correct')
                                            ✓
                                        @elseif($detail['status'] === 'wrong')
                                            ✗
                                        @else
                                            ?
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div class="p-4 bg-white rounded-lg border border-gray-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-user-edit text-gray-500 mr-2"></i>
                                        <div class="text-sm font-medium text-gray-600">Your Answer:</div>
                                    </div>
                                    <div class="mt-2">
                                        @if(is_array($detail['selected']))
                                            @if(count($detail['selected']) > 0)
                                                <div class="space-y-2">
                                                    @foreach($detail['selected'] as $selected)
                                                        <div class="px-3 py-2 bg-gray-100 rounded-lg text-gray-800">
                                                            {{ $selected }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="px-3 py-2 bg-gray-100 rounded-lg text-gray-500 italic">
                                                    <i class="fas fa-ban mr-2"></i>No answer selected
                                                </div>
                                            @endif
                                        @else
                                            <div class="px-3 py-2 bg-gray-100 rounded-lg text-gray-800">
                                                {{ $detail['selected'] ?? 'No answer' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="p-4 bg-white rounded-lg border border-gray-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <div class="text-sm font-medium text-gray-600">Correct Answer:</div>
                                    </div>
                                    <div class="mt-2">
                                        @if(is_array($detail['correct']))
                                            <div class="space-y-2">
                                                @foreach($detail['correct'] as $correct)
                                                    <div class="px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-green-800 font-medium">
                                                        {{ $correct }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="px-3 py-2 bg-green-50 border border-green-200 rounded-lg text-green-800 font-medium">
                                                {{ $detail['correct'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            @if($detail['status'] === 'wrong' && isset($detail['feedback']))
                                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-lightbulb text-blue-500 mr-2"></i>
                                        <span class="text-sm font-medium text-blue-700">Tip: {{ $detail['feedback'] }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions Card -->
            <div class="result-card animate-fadeInUp" style="animation-delay: 0.4s">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-rocket text-indigo-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">What's Next?</h3>
                            <p class="text-gray-600 text-sm">Continue your learning journey</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('student.dashboard') }}" class="primary-button">
                            <i class="fas fa-home mr-2"></i>
                            Back to Dashboard
                        </a>
                        
                        <a href="{{ route('student.practice-quiz.create') }}" class="secondary-button">
                            <i class="fas fa-dumbbell mr-2"></i>
                            Take Practice Quiz
                        </a>
                        
                        @if($percentage < 70)
                            <a href="{{ route('student.weak-areas') }}" class="secondary-button">
                                <i class="fas fa-chart-line mr-2"></i>
                                Review Weak Areas
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Motivational Tip -->
    @if(isset($motivationalTip) && $motivationalTip)
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
                        Motivational Message
                    </h3>
                    <p class="mt-2 text-gray-700 italic text-lg">"{{ $motivationalTip }}"</p>
                    @if($percentage >= 70)
                        <div class="mt-3 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                            <span class="text-sm text-gray-600 ml-2">Excellent work! Keep it up!</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to cards on scroll
            const cards = document.querySelectorAll('.question-item, .result-card');
            
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

            // Add confetti effect for high scores
            if ({{ $percentage }} >= 85) {
                setTimeout(() => {
                    createConfetti();
                }, 1000);
            }
        });

        function createConfetti() {
            const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#10b981', '#f59e0b'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = '50%';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-20px';
                confetti.style.opacity = '0.8';
                confetti.style.zIndex = '9999';
                document.body.appendChild(confetti);
                
                const animation = confetti.animate([
                    { transform: 'translateY(0) rotate(0deg)', opacity: 1 },
                    { transform: `translateY(${window.innerHeight + 20}px) rotate(${Math.random() * 360}deg)`, opacity: 0 }
                ], {
                    duration: Math.random() * 3000 + 2000,
                    easing: 'cubic-bezier(0.215, 0.610, 0.355, 1)'
                });
                
                animation.onfinish = () => confetti.remove();
            }
        }
    </script>
</body>
</html>