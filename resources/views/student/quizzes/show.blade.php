
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $quiz->title }} - Quiz</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-gradient: linear-gradient(135deg, #16a34a, #22c55e);
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

        .quiz-shell {
            max-width: 1100px;
            margin: auto;
        }

        .quiz-header {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
            transition: var(--transition-default);
        }

        .quiz-header:hover {
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
        }

        .question-card {
            background: white;
            border-radius: 1rem;
            padding: 1.75rem;
            border: 2px solid #e5e7eb;
            transition: var(--transition-default);
            margin-bottom: 1.5rem;
        }

        .question-card:hover {
            border-color: #c7d2fe;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .option-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: var(--transition-default);
            margin-bottom: 0.75rem;
        }

        .option-card:hover {
            background: #eef2ff;
            border-color: #a5b4fc;
            transform: translateX(5px);
        }

        .timer-box {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 2px solid #fee2e2;
            transition: var(--transition-default);
        }

        .timer-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .primary-btn {
            background: var(--success-gradient);
            border-radius: 0.75rem;
            font-weight: 600;
            transition: var(--transition-default);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .primary-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(34,197,94,0.3);
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

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-indigo {
            background: #e0e7ff;
            color: #4338ca;
        }

        .badge-gray {
            background: #f3f4f6;
            color: #4b5563;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
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

    <div class="py-8">
        <div class="quiz-shell px-4">

            <!-- Header -->
            <div class="quiz-header mb-8 flex flex-col lg:flex-row justify-between gap-6">
                <div class="animate-fadeInUp">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
                    <p class="text-gray-600 text-lg">{{ $quiz->description }}</p>

                    <div class="flex gap-3 mt-4">
                        <span class="badge badge-indigo">
                            <i class="fas fa-chart-line mr-1"></i>
                            Difficulty: {{ ucfirst($quiz->difficulty) }}
                        </span>
                        <span class="badge badge-gray">
                            <i class="fas fa-question-circle mr-1"></i>
                            Questions: {{ count($questions) }}
                        </span>
                        @if($quiz->course)
                            <span class="badge badge-blue">
                                <i class="fas fa-book mr-1"></i>
                                Course: {{ $quiz->course->title ?? 'Unknown' }}
                            </span>
                        @endif
                    </div>
                </div>

                @if($durationMinutes)
                <div id="timer-container" class="timer-box animate-fadeInUp">
                    <div class="text-sm text-red-600 font-medium mb-1 flex items-center">
                        <i class="fas fa-clock mr-2"></i>Time Remaining
                    </div>
                    <div id="timer" class="text-3xl font-bold text-red-700">
                        {{ $durationMinutes }}:00
                    </div>
                    <div class="text-xs text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Auto-submits when time ends
                    </div>
                </div>
                @endif
            </div>

            <!-- Quiz -->
            <form id="quiz-form" action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST">
                @csrf
                <input type="hidden" name="attempt_id" value="{{ $attemptId }}">

                <div class="space-y-6">
                    @foreach($questions as $question)
                        <div class="question-card animate-fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-start">
                                    <span class="text-lg font-bold text-indigo-600 mr-3 mt-1">
                                        Q{{ $loop->iteration }}.
                                    </span>
                                    <p class="font-semibold text-lg text-gray-900">
                                        {{ $question->question_text }}
                                    </p>
                                </div>

                                @if($question->points > 1)
                                    <span class="text-sm px-3 py-1 rounded-full bg-green-100 text-green-800 font-medium">
                                        <i class="fas fa-star mr-1"></i>
                                        {{ $question->points }} pts
                                    </span>
                                @endif
                            </div>

                            @if($question->question_type === 'mcq')
                                @php $options = json_decode($question->options, true); @endphp
                                <div class="space-y-3">
                                    @foreach($options as $option)
                                        <label class="option-card">
                                            <input type="checkbox"
                                                   name="answers[{{ $question->id }}][]"
                                                   value="{{ $option }}"
                                                   class="h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500">
                                            <span class="text-gray-700">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->question_type === 'true_false')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach(['True','False'] as $option)
                                        <label class="option-card">
                                            <input type="radio"
                                                   name="answers[{{ $question->id }}]"
                                                   value="{{ $option }}"
                                                   class="h-5 w-5 text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-gray-700">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->question_type === 'short_answer')
                                <div class="mt-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-keyboard text-gray-500 mr-2"></i>
                                        <span class="text-sm text-gray-600">Type your answer below:</span>
                                    </div>
                                    <input type="text"
                                           name="answers[{{ $question->id }}]"
                                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                           placeholder="Enter your answer here...">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-4 animate-fadeInUp">
                    <div class="flex items-center text-gray-500">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm">
                            You have answered {{ count($questions) }} questions
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button"
                                onclick="saveProgress()"
                                class="secondary-button">
                            <i class="fas fa-save mr-2"></i>
                            Save Progress
                        </button>

                        <button type="submit"
                                class="primary-btn">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Submit Quiz
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @if($durationMinutes)
    <script>
        let remainingSeconds = {{ $remainingSeconds ?? $durationMinutes * 60 }};
        let timerInterval;
        let isSubmitted = false;
        const attemptId = {{ $attemptId }};
        const quizId = {{ $quiz->id }};
        const saveEndpoint = "{{ route('student.quizzes.submit', $quiz->id) }}".replace('/submit', '/save-progress');

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function startTimer() {
            const timerElement = document.getElementById('timer');

            timerInterval = setInterval(() => {
                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    autoSubmitQuiz();
                    return;
                }

                remainingSeconds--;
                timerElement.textContent = formatTime(remainingSeconds);

                if (remainingSeconds < 60) {
                    timerElement.classList.remove('text-red-700');
                    timerElement.classList.add('text-red-500', 'animate-pulse');
                    document.getElementById('timer-container').style.borderColor = '#f87171';
                }

                if (remainingSeconds % 30 === 0) {
                    saveProgress();
                }

                localStorage.setItem(`quiz_timer_${attemptId}`, remainingSeconds);
                localStorage.setItem(`quiz_start_${attemptId}`, Date.now());

            }, 1000);

            timerElement.textContent = formatTime(remainingSeconds);
        }

        function autoSubmitQuiz() {
            if (isSubmitted) return;
            isSubmitted = true;

            const timerContainer = document.getElementById('timer-container');
            if (timerContainer) {
                timerContainer.innerHTML = `
                    <div class="text-center">
                        <div class="text-sm text-red-600 font-medium mb-1">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Time's Up!
                        </div>
                        <div class="text-lg font-bold text-red-700">Submitting...</div>
                    </div>
                `;
                timerContainer.style.borderColor = '#dc2626';
            }

            document.getElementById('quiz-form').submit();
        }

        function saveProgress() {
            const form = document.getElementById('quiz-form');
            const formData = new FormData(form);
            const saveData = new FormData();

            for (let [key, value] of formData.entries()) {
                if (key === '_token' || key === 'attempt_id' || key.startsWith('answers')) {
                    saveData.append(key, value);
                }
            }

            saveData.append('remaining_seconds', remainingSeconds);

            fetch(saveEndpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: saveData
            }).then(() => {
                const saveBtn = document.querySelector('button[onclick="saveProgress()"]');
                if (saveBtn) {
                    const originalText = saveBtn.innerHTML;
                    saveBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Saved!';
                    saveBtn.style.background = '#10b981';
                    saveBtn.style.color = 'white';
                    
                    setTimeout(() => {
                        saveBtn.innerHTML = originalText;
                        saveBtn.style.background = '';
                        saveBtn.style.color = '';
                    }, 2000);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const storedTime = localStorage.getItem(`quiz_timer_${attemptId}`);
            const storedStart = localStorage.getItem(`quiz_start_${attemptId}`);

            if (storedTime && storedStart) {
                const elapsed = Math.floor((Date.now() - storedStart) / 1000);
                remainingSeconds = Math.max(0, parseInt(storedTime) - elapsed);
            }

            startTimer();

            window.addEventListener('beforeunload', function(e) {
                if (!isSubmitted) {
                    saveProgress();
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            // Add animation to cards on scroll
            const cards = document.querySelectorAll('.question-card');
            
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
        });
    </script>
    @endif

    <!-- Motivational Tip -->
    @if(isset($motivationalTip) && $motivationalTip)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-8">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-l-4 border-indigo-500 rounded-r-lg p-6 animate-fadeInUp">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-lightbulb text-indigo-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Motivational Tip</h3>
                    <p class="mt-1 text-gray-700 italic">"{{ $motivationalTip }}"</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</body>
</html>