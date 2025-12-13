<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'App')</title>

    {{-- Keep Vite import exactly as it is (do not remove) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="margin:40px; font-family:Arial;">

    {{-- Header (optional) --}}
    <header>
        <h2 style="margin-bottom:20px;">Smart Learning Platform and Schedule Planner</h2>
        <hr>
    </header>

    {{-- Main wrapper: centered container. Safe if Tailwind is enabled; harmless otherwise --}}
    <main style="margin-top:20px;">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-sm">
            @yield('content')
        </div>
    </main>

    {{-- Small footer area, non-functional — purely for consistent spacing --}}
    <footer class="mt-6 text-sm text-center text-gray-600">
        <p>© {{ date('Y') }} Smart Learning Platform</p>
    </footer>

</body>
</html>
