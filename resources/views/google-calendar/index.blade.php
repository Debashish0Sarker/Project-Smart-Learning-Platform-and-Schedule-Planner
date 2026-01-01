<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Calendar - Smart Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Simple Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <a href="/" class="text-blue-600 font-bold">← Back to Home</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Google Calendar Integration</h1>
                
                @if(session('google_token'))
                    <a href="{{ route('google-calendar.disconnect') }}" 
                       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                        Disconnect Google Calendar
                    </a>
                @else
                    <a href="{{ route('google-calendar.connect') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Connect Google Calendar
                    </a>
                @endif
            </div>

            <!-- Status Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Connection Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Connection Status</h2>
                <div class="flex items-center">
                    @if(session('google_token'))
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-green-600 font-medium">Connected to Google Calendar</span>
                    @else
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-red-600">Not connected to Google Calendar</span>
                    @endif
                </div>
            </div>

            <!-- Demo Mode Notice (if applicable) -->
            @if(session('google_token') && session('google_token')['access_token'] === 'test_token')
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                    <strong>Demo Mode:</strong> You're using demo mode. For real Google Calendar integration, 
                    please configure Google API credentials in your .env file.
                </div>
            @endif

            <!-- Instructions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">How to Use</h2>
                <ol class="list-decimal pl-5 space-y-3 text-gray-700">
                    <li>Click "Connect Google Calendar" button above</li>
                    <li>If in demo mode, you'll be automatically connected</li>
                    <li>If using real Google API, you'll be redirected to Google to authorize</li>
                    <li>Once connected, you can add courses/quizzes to your calendar</li>
                    <li>Visit your Google Calendar to see the added events</li>
                </ol>
                
                <!-- For Teachers/Students -->
                @php
                    $user = auth()->user();
                @endphp
                
                @if($user && $user->isTeacher())
                    <div class="mt-6 p-4 bg-blue-50 rounded">
                        <h3 class="font-semibold text-blue-800">Teacher Features:</h3>
                        <ul class="list-disc pl-5 mt-2 text-blue-700">
                            <li>Add your courses to Google Calendar</li>
                            <li>Schedule quizzes with automatic calendar reminders</li>
                            <li>Students will see these in their connected calendars</li>
                        </ul>
                    </div>
                @elseif($user && $user->isStudent())
                    <div class="mt-6 p-4 bg-green-50 rounded">
                        <h3 class="font-semibold text-green-800">Student Benefits:</h3>
                        <ul class="list-disc pl-5 mt-2 text-green-700">
                            <li>Sync course schedules automatically</li>
                            <li>Get quiz/exam reminders</li>
                            <li>Never miss deadlines with calendar notifications</li>
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Test Buttons (For Demo) -->
            @if(session('google_token'))
            <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Test Calendar Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 p-4 rounded">
                        <h3 class="font-semibold mb-2">Test Course Event</h3>
                        <p class="text-gray-600 mb-3">Simulate adding a course to calendar</p>
                        <form method="POST" action="{{ route('google-calendar.events.course', 1) }}">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Add Test Course
                            </button>
                        </form>
                    </div>
                    <div class="border border-gray-200 p-4 rounded">
                        <h3 class="font-semibold mb-2">Test Quiz Event</h3>
                        <p class="text-gray-600 mb-3">Simulate adding a quiz to calendar</p>
                        <form method="POST" action="{{ route('google-calendar.events.quiz', 1) }}">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                Add Test Quiz
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-12 text-center text-gray-500 text-sm">
        <p>Smart Learning Platform - Google Calendar Integration</p>
    </footer>
</body>
</html>