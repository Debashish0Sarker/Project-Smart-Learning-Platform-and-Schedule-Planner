{{-- resources/views/student/practice-quiz/no-api-key.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QuizAPI Configuration Required</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <header class="mb-4 text-center">
                <h1 class="text-2xl font-semibold text-gray-800">QuizAPI Configuration Required</h1>
            </header>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <div class="text-6xl mb-4">🔑</div>
                    <h1 class="text-3xl font-bold mb-4">QuizAPI Key Required</h1>
                    
                    <p class="text-gray-600 mb-6 text-lg">
                        To use the practice quiz feature, you need a QuizAPI key.
                    </p>
                    
                    <div class="text-left bg-gray-100 p-6 rounded-lg mb-6 max-w-2xl mx-auto">
                        <p class="font-semibold mb-3 text-gray-800">Add to your <code class="bg-gray-200 px-2 py-1 rounded">.env</code> file:</p>
                        <div class="bg-gray-800 text-green-400 p-4 rounded font-mono text-sm overflow-x-auto">
                            QUIZAPI_KEY=your_api_key_here<br>
                            QUIZAPI_BASE_URL=https://quizapi.io/api/v1
                        </div>
                    </div>
                    
                    <div class="text-left mb-8 max-w-2xl mx-auto">
                        <p class="font-semibold mb-3 text-gray-800">Get your free API key:</p>
                        <ol class="list-decimal pl-5 space-y-2 text-gray-600">
                            <li>Go to <a href="https://quizapi.io/" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium">quizapi.io</a></li>
                            <li>Sign up for a free account (100 requests/day)</li>
                            <li>Copy your API key from dashboard</li>
                            <li>Add it to your .env file</li>
                            <li>Clear config cache: <code class="bg-gray-200 px-2 py-1 rounded text-sm">php artisan config:clear</code></li>
                        </ol>
                    </div>
                    
                    <div class="space-x-4">
                        <a href="{{ route('student.dashboard') }}" 
                           class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2"
                           style="color: #1f2937 !important; background-color: #d1d5db !important; border: 2px solid #6b7280;">
                            <span class="flex items-center" style="color: inherit;">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #4b5563;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span style="font-weight: 700; color: #4b5563;">Back to Dashboard</span>
                            </span>
                        </a>
                        <a href="https://quizapi.io/" target="_blank" 
                           class="inline-flex justify-center py-2 px-6 border shadow-sm text-sm font-medium rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2"
                           style="color: #1f2937 !important; background-color: #e5e7eb !important; border: 2px solid #9ca3af;">
                            <span class="flex items-center" style="color: inherit;">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #4b5563;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                <span style="font-weight: 700; color: #4b5563;">Get API Key</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>