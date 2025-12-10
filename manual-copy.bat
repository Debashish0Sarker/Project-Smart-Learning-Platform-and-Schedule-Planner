@echo off
echo ========================================
echo MANUAL FEATURE 3 COPY SCRIPT
echo ========================================
echo.
echo Source: C:\xampp\htdocs\smart_learning_app\smart-learning-platform
echo Destination: %CD%
echo.

echo Creating directories...
mkdir "app\Http\Controllers\Teacher" 2>nul
mkdir "app\Models" 2>nul
mkdir "database\migrations" 2>nul
mkdir "database\seeders" 2>nul
mkdir "resources\views\teacher" 2>nul
mkdir "resources\views\teacher\quizzes" 2>nul
mkdir "resources\views\teacher\courses" 2>nul
mkdir "resources\views\layouts" 2>nul
mkdir "resources\views\student" 2>nul

echo.
echo Copying files...
echo.

REM Copy controllers
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\app\Http\Controllers\Teacher\QuizController.php" "app\Http\Controllers\Teacher\" /Y

REM Copy models
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\app\Models\Quiz.php" "app\Models\" /Y
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\app\Models\Question.php" "app\Models\" /Y
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\app\Models\Enrollment.php" "app\Models\" /Y

REM Copy views
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\resources\views\teacher\*.blade.php" "resources\views\teacher\" /Y /S
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\resources\views\teacher\quizzes\*.blade.php" "resources\views\teacher\quizzes\" /Y
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\resources\views\teacher\courses\*.blade.php" "resources\views\teacher\courses\" /Y
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\resources\views\layouts\teacher.blade.php" "resources\views\layouts\" /Y

REM Copy migrations
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\database\migrations\*_create_quizzes_table.php" "database\migrations\" /Y
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\database\migrations\*_create_questions_table.php" "database\migrations\" /Y
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\database\migrations\*_create_courses_table.php" "database\migrations\" /Y
xcopy "C:\xampp\htdocs\smart_learning_app\smart-learning-platform\database\migrations\*_add_role_to_users_table.php" "database\migrations\" /Y

echo.
echo ========================================
echo FILES COPIED SUCCESSFULLY!
echo ========================================
echo.
echo NEXT STEPS:
echo 1. MANUALLY MERGE these files:
echo    - routes\web.php (add teacher routes)
echo    - app\Models\User.php (add teacher methods)
echo    - app\Models\Course.php (add relationships)
echo.
echo 2. Run: php artisan migrate
echo.
echo 3. Test: php artisan serve
echo    Visit: http://localhost:8000/teacher/quizzes/create
echo.
pause