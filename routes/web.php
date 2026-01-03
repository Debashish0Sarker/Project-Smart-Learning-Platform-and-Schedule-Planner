<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;
use App\Http\Controllers\Teacher\NotificationController as TeacherNotificationController;
use App\Http\Controllers\Student\WeakAreaController;
use App\Http\Controllers\OneSignalController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Student\PracticeQuizController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

// Authentication Routes (From Amio)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Add this after your authentication routes
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    
    // Redirect based on user role
    if ($user->isStudent()) {
        return redirect()->route('student.dashboard');
    } elseif ($user->isTeacher()) {
        return redirect()->route('teacher.dashboard');
    }
    
    // Fallback for other users
    return view('home');
})->name('dashboard');


// Protected Student Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Courses
    Route::get('/courses', [\App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [\App\Http\Controllers\Student\CourseController::class, 'showDetails'])->name('courses.details');
    Route::post('/courses/enroll', [\App\Http\Controllers\Student\CourseController::class, 'enroll'])->name('courses.enroll');
    
    // Quizzes
    Route::get('/quizzes', [\App\Http\Controllers\Student\QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{quiz}', [\App\Http\Controllers\Student\QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [\App\Http\Controllers\Student\QuizController::class, 'submitQuiz'])->name('quizzes.submit');
    Route::post('/quizzes/{quiz}/save-progress', [\App\Http\Controllers\Student\QuizController::class, 'saveProgress'])->name('quizzes.save-progress');
    

        // Motivational Tip Routes
    Route::get('/motivational-tip/refresh', function() {
        // Clear cache and get new tip
        Cache::forget('motivational_tip');
        $tip = \App\Http\Controllers\Student\MotivationalTipController::getTip();
        return response()->json(['success' => true, 'tip' => $tip]);
    })->name('motivational-tip.refresh');

    // Course Materials Routes
    Route::get('/courses/{course}/materials', [\App\Http\Controllers\Student\CourseMaterialController::class, 'index'])
        ->name('course.materials');
        
    Route::get('/materials/{material}', [\App\Http\Controllers\Student\CourseMaterialController::class, 'show'])
        ->name('material.show');
        
    Route::get('/materials/{material}/download', [\App\Http\Controllers\Student\CourseMaterialController::class, 'download'])
        ->name('material.download');
        
    Route::post('/materials/{material}/mark-viewed', function($material) {
        // Placeholder for marking material as viewed
        return response()->json(['success' => true]);
    })->name('material.mark-viewed');

    // Courses index page (full list)
    Route::get('/courses', function() {
        $courses = \App\Models\Course::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return view('student.courses-index', compact('courses'));
    })->name('courses.index');



    
    // SUBMISSION TRACKER ROUTES - ADD THESE
    Route::get('/submission-tracker', [\App\Http\Controllers\Student\SubmissionTrackerController::class, 'index'])->name('submission-tracker.index');
    Route::get('/submission-tracker/{quizResponse}', [\App\Http\Controllers\Student\SubmissionTrackerController::class, 'show'])->name('submission-tracker.show');
    
    // Weak areas
    Route::get('/weak-areas', [\App\Http\Controllers\Student\WeakAreaController::class, 'show'])->name('weak-areas');
    Route::post('/weak-areas/enroll', [\App\Http\Controllers\Student\WeakAreaController::class, 'enroll'])->name('weak-areas.enroll');
    // Add notification routes HERE (after other student routes):
    Route::get('/notifications', [\App\Http\Controllers\Student\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\Student\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Student\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\Student\NotificationController::class, 'delete'])->name('notifications.delete');
    Route::delete('/notifications', [\App\Http\Controllers\Student\NotificationController::class, 'clearAll'])->name('notifications.clear-all');

    // OneSignal web push: register player id and send-test
    Route::post('/onesignal/register', [OneSignalController::class, 'register'])->name('onesignal.register');
    Route::post('/onesignal/send-test', [OneSignalController::class, 'sendTest'])->name('onesignal.send-test');

    // Practice Quiz Routes (student-scoped)
    Route::prefix('practice-quiz')->name('practice-quiz.')->group(function () {
    Route::get('/', [PracticeQuizController::class, 'create'])->name('index');
    Route::get('/create', [PracticeQuizController::class, 'create'])->name('create');
    Route::post('/generate', [PracticeQuizController::class, 'generate'])->name('generate');
    Route::post('/submit', [PracticeQuizController::class, 'submit'])->name('submit');
});


});
    
    Route::get('/weak-areas', [\App\Http\Controllers\Student\WeakAreaController::class, 'show'])
        ->name('weak-areas');
    Route::post('/weak-areas/enroll', [\App\Http\Controllers\Student\WeakAreaController::class, 'enroll'])
        ->name('weak-areas.enroll');

    // Practice Quiz Routes
    // NOTE: moved into student group earlier to keep routes under /student and namespaced as student.practice-quiz.*
    
    // Schedule (simple view route)
    Route::get('/schedule', function () {
        return view('student.schedule');
    })->name('schedule');
//});

// Also provide top-level routes so `/weak-areas` resolves (and blade route('weak-areas') works)
Route::middleware(['auth'])->get('/weak-areas', [WeakAreaController::class, 'show'])->name('weak-areas');
Route::middleware(['auth'])->post('/weak-areas/enroll', [WeakAreaController::class, 'enroll'])->name('weak-areas.enroll');

// Protected Teacher Routes
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Courses - RESTful resource (7 routes total)
    Route::resource('courses', \App\Http\Controllers\Teacher\CourseController::class);
    
    // Quizzes - RESTful resource (7 routes total)
    Route::resource('quizzes', \App\Http\Controllers\Teacher\QuizController::class);
    
    // SUBMISSION MANAGEMENT ROUTES - ADD THESE
    Route::get('/quizzes/{quiz}/submissions', [\App\Http\Controllers\Teacher\SubmissionController::class, 'quizSubmissions'])
        ->name('quizzes.submissions');
    Route::get('/submissions/{quizResponse}', [\App\Http\Controllers\Teacher\SubmissionController::class, 'showSubmission'])
        ->name('submissions.show');
    Route::post('/submissions/{quizResponse}/grade', [App\Http\Controllers\Teacher\SubmissionController::class, 'gradeSubmission'])
        ->name('submissions.grade');
    
    // Course Materials Routes - FIXED NAMES
    Route::get('/courses/{course}/materials', [\App\Http\Controllers\Teacher\ResourceController::class, 'index'])
        ->name('courses.materials.index');  // This becomes teacher.courses.materials.index
        
    Route::get('/courses/{course}/materials/create', [\App\Http\Controllers\Teacher\ResourceController::class, 'create'])
        ->name('courses.materials.create');  // This becomes teacher.courses.materials.create
        
    Route::post('/courses/{course}/materials', [\App\Http\Controllers\Teacher\ResourceController::class, 'store'])
        ->name('courses.materials.store');  // This becomes teacher.courses.materials.store
         // Add teacher notification routes
    Route::get('/notifications', [\App\Http\Controllers\Teacher\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\Teacher\NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Teacher\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\Teacher\NotificationController::class, 'delete'])->name('notifications.delete');
    Route::delete('/notifications', [\App\Http\Controllers\Teacher\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
});


// Test routes (development only - can remove later)
Route::get('/feature3-test', function() {
    return 'Feature 3: Teacher Quiz Creation - Working';
});

// COMMENT OUT or REMOVE these problematic test routes
// Route::get('/weak-areas-test', [\App\Http\Controllers\Student\WeakAreaController::class, 'test'])->name('weak-areas.test');
// Route::get('/weak-areas/enroll-test/{courseId}', [\App\Http\Controllers\Student\WeakAreaController::class, 'enrollTest'])->name('weak-areas.enroll-test');
// Route::get('/weak-areas/clear-enrollments', [\App\Http\Controllers\Student\WeakAreaController::class, 'clearTestEnrollments'])->name('weak-areas.clear-enrollments');
// Add these routes after the existing student/teacher routes
// In web.php, add these routes:

// Google Calendar Routes - CORRECTED
Route::middleware(['auth'])->group(function () {
    Route::prefix('google-calendar')->name('google-calendar.')->group(function () {
        // Main calendar page
        Route::get('/', [App\Http\Controllers\GoogleCalendarController::class, 'index'])->name('index');
        
        // OAuth flow
        Route::get('/connect', [App\Http\Controllers\GoogleCalendarController::class, 'connect'])->name('connect');
        Route::get('/callback', [App\Http\Controllers\GoogleCalendarController::class, 'callback'])->name('callback');
        Route::get('/disconnect', [App\Http\Controllers\GoogleCalendarController::class, 'disconnect'])->name('disconnect');
        
        // Event operations
        Route::post('/events', [App\Http\Controllers\GoogleCalendarController::class, 'createEvent'])->name('events.create');
        Route::delete('/events/{eventId}', [App\Http\Controllers\GoogleCalendarController::class, 'deleteEvent'])->name('events.delete');
        
        // Quick add events
        Route::post('/events/course/{course}', [App\Http\Controllers\GoogleCalendarController::class, 'createCourseEvent'])->name('events.course');
        Route::post('/events/quiz/{quiz}', [App\Http\Controllers\GoogleCalendarController::class, 'createQuizEvent'])->name('events.quiz');
    });
});
// newss
// News Routes
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/articles', [NewsController::class, 'getNews'])->name('news.articles');
    Route::get('/check-api', [NewsController::class, 'checkApi'])->name('news.check');
});

// News Routes
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/articles', [NewsController::class, 'getNews'])->name('news.articles');
    Route::get('/config', [NewsController::class, 'checkConfig'])->name('news.config');
    Route::get('/article/{id}', [NewsController::class, 'show'])->name('news.show');
});

//test
Route::get('/debug-newsapi', function() {
    $apiKey = config('services.newsapi.key');
    
    // Test 1: Different endpoints
    $tests = [
        'top-headlines US' => "https://newsapi.org/v2/top-headlines?country=us&apiKey={$apiKey}",
        'top-headlines tech' => "https://newsapi.org/v2/top-headlines?category=technology&country=us&apiKey={$apiKey}",
        'everything tech' => "https://newsapi.org/v2/everything?q=technology&language=en&apiKey={$apiKey}",
    ];
    
    $results = [];
    
    foreach ($tests as $name => $url) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HEADER => true, // Get headers
            CURLOPT_HTTPHEADER => [
                'User-Agent: Mozilla/5.0 (compatible; Laravel/10.0)',
            ],
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        
        curl_close($ch);
        
        $data = json_decode($body, true);
        
        $results[$name] = [
            'url' => str_replace($apiKey, '***', $url),
            'http_code' => $httpCode,
            'error' => $error,
            'status' => $data['status'] ?? 'error',
            'code' => $data['code'] ?? null,
            'message' => $data['message'] ?? null,
            'totalResults' => $data['totalResults'] ?? 0,
        ];
    }
    
    // Test 2: Check if key is blocked/banned
    $keyTestUrl = "https://newsapi.org/v2/sources?apiKey={$apiKey}";
    $ch = curl_init($keyTestUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 10,
    ]);
    $keyTestResponse = curl_exec($ch);
    $keyTestCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $keyData = json_decode($keyTestResponse, true);
    
    return response()->json([
        'api_key_preview' => substr($apiKey, 0, 8) . '...',
        'tests' => $results,
        'key_status_test' => [
            'endpoint' => 'sources',
            'http_code' => $keyTestCode,
            'status' => $keyData['status'] ?? 'error',
            'message' => $keyData['message'] ?? null,
            'sources_count' => isset($keyData['sources']) ? count($keyData['sources']) : 0,
        ],
        'possible_issues' => [
            'rate_limit' => 'Free tier: 100 requests/day',
            'ssl_issue' => 'Try with verify => false',
            'user_agent' => 'Some APIs require User-Agent',
            'parameter_error' => 'Invalid parameters might cause 400',
        ]
    ]);
});