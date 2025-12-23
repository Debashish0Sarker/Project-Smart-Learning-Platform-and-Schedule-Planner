{{-- resources/views/student/practice-quiz/create.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Practice Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Force button text visibility */
        button[type="submit"] {
            color: white !important;
            -webkit-text-fill-color: white !important;
        }
        button[type="submit"] span {
            color: inherit !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <header class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Practice Quiz</h1>
            </header>

            <main>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-2xl font-bold mb-6 text-gray-800">Take a Practice Quiz</h1>
                        
                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('student.practice-quiz.generate') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2 font-medium">Topic</label>
                                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700" required>
                                    <option value="">Select a topic</option>
                                    <option value="random">Random Topics</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2 font-medium">Difficulty</label>
                                <select name="difficulty" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700">
                                    @foreach($difficulties as $difficulty)
                                        <option value="{{ $difficulty }}">{{ ucfirst($difficulty) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-gray-700 mb-2 font-medium">Number of Questions</label>
                                <select name="question_count" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700">
                                    @foreach($questionCounts as $count)
                                        <option value="{{ $count }}">{{ $count }} questions</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- FIXED BUTTON WITH VISIBLE TEXT -->
                            <button type="submit" 
                                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
                                    style="color: white !important; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                                <span class="flex items-center justify-center" style="color: inherit;">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span style="font-weight: 600;">Start Quiz</span>
                                </span>
                            </button>
                        </form>
                        
                        <div class="mt-6 text-sm text-gray-600">
                            <p class="text-gray-700">Questions are fetched from QuizAPI in real-time.</p>
                            <p class="text-gray-700">Results are not saved - practice at your own pace!</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>