@echo off
echo Setting up Feature 3 Development Environment...

cd /d C:\xampp\htdocs\smart_learning_app\smart-learning-platform

echo 1. Ensuring teacher exists...
php artisan tinker --execute="
// Create teacher if not exists
if (!\App\Models\User::where('role', 'teacher')->exists()) {
    \App\Models\User::create([
        'name' => 'Development Teacher',
        'email' => 'dev@teacher.com',
        'password' => \Illuminate\Support\Facades\Hash::make('dev123'),
        'role' => 'teacher',
        'email_verified_at' => now(),
    ]);
    echo 'Teacher created: dev@teacher.com / dev123\\n';
}

// Create course if not exists
if (\App\Models\Course::count() == 0) {
    \$teacher = \App\Models\User::where('role', 'teacher')->first();
    \App\Models\Course::create([
        'title' => 'Software Development',
        'code' => 'CSE471',
        'description' => 'Test course for development',
        'teacher_id' => \$teacher->id,
        'category' => 'Computer Science',
    ]);
    echo 'Course created: CSE471\\n';
}
" 2>nul

echo 2. Clearing caches...
php artisan optimize:clear 2>nul

echo 3. Starting server...
echo.
echo Open these URLs:
echo 1. http://localhost:8000/teacher/dashboard
echo 2. http://localhost:8000/teacher/quizzes/create
echo 3. http://localhost:8000/feature3-test
echo.
start php artisan serve

timeout /t 3
start http://localhost:8000/teacher/dashboard

pause