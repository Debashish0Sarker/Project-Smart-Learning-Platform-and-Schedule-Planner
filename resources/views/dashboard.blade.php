<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-6xl mx-auto p-6">

        <h1 class="text-3xl font-bold mb-6">Teacher Dashboard</h1>
        <div class="flex gap-4 mb-6">
    <a href="{{ route('courses.create') }}"
       class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
        ➕ Create Course
    </a>

    <a href="{{ route('courses.showcourse') }}"
       class="bg-green-600 text-white px-5 py-2 rounded-lg shadow hover:bg-green-700">
        📚 View Courses
    </a>
</div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            <div class="bg-white shadow p-6 rounded-xl">
                <h3 class="text-gray-500">Total Students</h3>
                <p class="text-4xl font-bold">124</p>
            </div>

            <div class="bg-white shadow p-6 rounded-xl">
                <h3 class="text-gray-500">Total Courses</h3>
                <p class="text-4xl font-bold">8</p>
            </div>

            <div class="bg-white shadow p-6 rounded-xl">
                <h3 class="text-gray-500">Completed Courses</h3>
                <p class="text-4xl font-bold">3</p>
            </div>

        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Activity Hours Chart -->
            <div class="bg-white shadow p-6 rounded-xl">
                <h3 class="font-semibold mb-4">Weekly Activity Hours</h3>
                <canvas id="activityChart"></canvas>
            </div>

            <!-- Average Grades Chart -->
            <div class="bg-white shadow p-6 rounded-xl">
                <h3 class="font-semibold mb-4">Average Grades</h3>
                <canvas id="gradeChart"></canvas>
            </div>

        </div>

    </div>

    <script>
        // Activity Chart (Dummy Data)
        new Chart(document.getElementById('activityChart'), {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                datasets: [{
                    label: "Hours",
                    data: [2, 4, 3, 6, 5, 2],
                    borderWidth: 2
                }]
            }
        });

        // Average Grades Chart (Dummy Data)
        new Chart(document.getElementById('gradeChart'), {
            type: 'bar',
            data: {
                labels: ['Quiz 1', 'Quiz 2', 'Quiz 3'],
                datasets: [{
                    label: "Average Score",
                    data: [78, 85, 91],
                    borderWidth: 2
                }]
            }
        });
    </script>

</body>
</html>
