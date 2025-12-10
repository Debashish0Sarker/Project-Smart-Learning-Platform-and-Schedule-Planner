<?php
// test-feature3-setup.php
require __DIR__.'/vendor/autoload.php';

echo "<h1>Feature 3 Setup Test</h1>";

// Check required files
$files = [
    'Controller' => 'app/Http/Controllers/Teacher/QuizController.php',
    'Layout' => 'resources/views/layouts/teacher.blade.php',
    'Index View' => 'resources/views/teacher/quizzes/index.blade.php',
    'Create View' => 'resources/views/teacher/quizzes/create.blade.php',
    'Show View' => 'resources/views/teacher/quizzes/show.blade.php',
    'Edit View' => 'resources/views/teacher/quizzes/edit.blade.php',
];

foreach ($files as $name => $file) {
    if (file_exists($file)) {
        echo "<p style='color:green;'>✓ $name: $file</p>";
    } else {
        echo "<p style='color:red;'>✗ $name: $file (MISSING)</p>";
    }
}

echo "<h3>Test URLs:</h3>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/teacher/dashboard'>Teacher Dashboard</a></li>";
echo "<li><a href='http://localhost:8000/teacher/quizzes'>Quiz List</a></li>";
echo "<li><a href='http://localhost:8000/teacher/quizzes/create'>Create Quiz</a></li>";
echo "</ul>";
?>