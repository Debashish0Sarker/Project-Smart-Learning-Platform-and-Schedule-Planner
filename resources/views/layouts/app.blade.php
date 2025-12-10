<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'App')</title>

    {{-- Later you will add Tailwind here --}}
    {{-- <script src="...tailwind..."></script> --}}
</head>

<body style="margin:40px; font-family:Arial;">

    {{-- Header (optional) --}}
    <header>
        <h2 style="margin-bottom:20px;">Smart Learning Platform and Schedule Planner</h2>
        <hr>
    </header>

    <main style="margin-top:20px;">
        @yield('content')
    </main>

</body>
</html>
