<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ $quiz->title }}
        </h2>
    </x-slot>

    <style>
        .quiz-shell {
            max-width: 1100px;
            margin: auto;
        }

        .quiz-header {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .question-card {
            background: white;
            border-radius: 1rem;
            padding: 1.75rem;
            border: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }

        .question-card:hover {
            border-color: #c7d2fe;
            background: #fafafa;
        }

        .option-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .option-card:hover {
            background: #eef2ff;
            border-color: #a5b4fc;
        }

        .timer-box {
            position: sticky;
            top: 1rem;
            z-index: 20;
        }

        .primary-btn {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .primary-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(34,197,94,0.3);
        }
    </style>

    <div class="py-10">
        <div class="quiz-shell px-4">

            <!-- Header -->
            <div class="quiz-header mb-8 flex flex-col lg:flex-row justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $quiz->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>

                    <div class="flex gap-3 mt-4 text-sm">
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700">
                            Difficulty: {{ ucfirst($quiz->difficulty) }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                            Questions: {{ count($questions) }}
                        </span>
                    </div>
                </div>

                @if($durationMinutes)
                <div id="timer-container" class="timer-box bg-red-50 border border-red-200 rounded-xl p-4 w-48 text-center">
                    <div class="text-sm text-red-600 font-medium mb-1">Time Remaining</div>
                    <div id="timer" class="text-3xl font-bold text-red-700">
                        {{ $durationMinutes }}:00
                    </div>
                    <div class="text-xs text-red-500 mt-1">
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
                        <div class="question-card">
                            <div class="flex justify-between items-start mb-4">
                                <p class="font-semibold text-lg text-gray-900">
                                    Q{{ $loop->iteration }}. {{ $question->question_text }}
                                </p>

                                @if($question->points > 1)
                                    <span class="text-sm px-3 py-1 rounded-full bg-gray-100 text-gray-600">
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
                                                   class="h-4 w-4 text-indigo-600">
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->question_type === 'true_false')
                                <div class="space-y-3">
                                    @foreach(['True','False'] as $option)
                                        <label class="option-card">
                                            <input type="radio"
                                                   name="answers[{{ $question->id }}]"
                                                   value="{{ $option }}"
                                                   class="h-4 w-4 text-indigo-600">
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->question_type === 'short_answer')
                                <input type="text"
                                       name="answers[{{ $question->id }}]"
                                       class="w-full mt-2 px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Type your answer here">
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="mt-10 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-500">
                        You have answered {{ count($questions) }} questions
                    </p>

                    <div class="flex gap-3">
                        <button type="button"
                                onclick="saveProgress()"
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            Save Progress
                        </button>

                        <button type="submit"
                                class="px-6 py-2 primary-btn text-white">
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
                        <div class="text-sm text-red-600 font-medium mb-1">Time's Up!</div>
                        <div class="text-lg font-bold text-red-700">Submitting...</div>
                    </div>
                `;
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
        });
    </script>
    @endif
    @include('student.partials.motivational-tip')
</x-app-layout>
