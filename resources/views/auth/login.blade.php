<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Student Portal</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --error-color: #ef4444;
            --card-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            --transition-default: all 0.3s ease;
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
            animation: fadeInUp 0.6s ease-out;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            transition: var(--transition-default);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-input.error {
            border-color: var(--error-color);
        }

        .auth-button {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            color: white;
            padding: 1.1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: var(--transition-default);
        }

        .auth-button:hover {
            background: linear-gradient(135deg, var(--primary-hover), #7c3aed);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">

        <!-- Card -->
        <div class="auth-card">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto rounded-full bg-indigo-500 flex items-center justify-center text-white text-2xl mb-4">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Welcome Back</h1>
                <p class="text-gray-600 mt-2">Sign in to continue</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                        placeholder="you@testmail.com"
                    >
                    @if($errors->has('email'))
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="Enter your password"
                    >
                    @if($errors->has('password'))
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-indigo-600 hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="auth-button">
                    <i class="fas fa-arrow-right-to-bracket mr-2"></i>
                    Log In
                </button>

                <!-- Register -->
                <div class="text-center mt-6 text-sm text-gray-600">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">
                        Create one
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
</body>
</html>
