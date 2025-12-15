@extends('layouts.app')

@section('title', 'Login | Smart Learning Platform')

@section('content')
<div class="min-h-[calc(100vh-200px)] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo/Platform Name -->
        <div class="text-center">
            <div class="flex justify-center items-center space-x-3 mb-4">
                <div class="h-10 w-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">SmartLearn</h1>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Welcome Back
            </h2>
            <p class="mt-3 text-gray-600 max-w-md mx-auto">
                Sign in to continue your learning journey
            </p>
        </div>

        <!-- Success/Error Messages -->
        @if($errors->any())
        <div class="mt-8" role="alert">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-900">
                            Unable to sign in
                        </h3>
                        <div class="mt-2 text-sm text-red-800">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="mt-8" role="alert">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-900">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 sm:px-10 rounded-2xl shadow-xl border border-gray-100">
            <form class="space-y-6" action="/login" method="POST">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-900">
                        Email Address
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="mt-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" 
                               autocomplete="email" required
                               value="{{ old('email') }}"
                               class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200 text-gray-900 text-sm"
                               placeholder="john@example.com">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-semibold text-gray-900">
                            Password
                            <span class="text-red-600">*</span>
                        </label>
                        <div class="text-sm">
                            <a href="/forgot-password" 
                               class="font-medium text-blue-600 hover:text-blue-800 transition duration-200 flex items-center group">
                                <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Forgot password?
                            </a>
                        </div>
                    </div>
                    <div class="mt-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" 
                            autocomplete="current-password" required
                            class="block w-full pl-10 pr-12 py-3.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200 text-gray-900 text-sm"
                            placeholder="••••••••">
                        <!-- Show password toggle -->
                        <button type="button" 
        id="toggle-password-btn"
        class="absolute inset-y-0 right-0 z-10 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200">
    <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <!-- Open Eye (default - password hidden) -->
        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path class="eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        <!-- Slashed Eye (password visible) -->
        <path class="eye-slash hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
    </svg>
</button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                        <span class="flex items-center">
                            Sign In
                            <svg class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Divider -->
            

                

            <!-- Sign Up Link -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-700">
                    Don't have an account? 
                    <a href="{{ url('/register') }}" 
                       class="font-medium text-blue-600 hover:text-blue-800 inline-flex items-center group transition duration-200">
                        Create account
                        <svg class="ml-1 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </p>
            </div>
        </div>

        

        <!-- Security Note -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center space-x-2 text-xs text-gray-500 bg-gray-50 px-4 py-2 rounded-full">
                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Your session is secured with HTTPS & 256-bit encryption</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('toggle-password-btn');
        const eyeOpenPaths = document.querySelectorAll('.eye-open');
        const eyeSlashPath = document.querySelector('.eye-slash');

        // Toggle password visibility
        toggleButton.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpenPaths.forEach(path => path.classList.add('hidden'));
                eyeSlashPath.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpenPaths.forEach(path => path.classList.remove('hidden'));
                eyeSlashPath.classList.add('hidden');
            }
        });

        // Add focus styling to form fields
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            const parent = input.closest('.relative');
            if (!parent) return;

            input.addEventListener('focus', function() {
                parent.classList.add('ring-2', 'ring-blue-200');
            });
            
            input.addEventListener('blur', function() {
                parent.classList.remove('ring-2', 'ring-blue-200');
            });
        });

        // Auto-focus email field
        document.getElementById('email')?.focus();

        // Demo credentials quick fill
        const demoTexts = document.querySelectorAll('.text-xs.text-blue-800 div');
        demoTexts.forEach(textDiv => {
            textDiv.style.cursor = 'pointer';
            textDiv.addEventListener('click', function() {
                const text = this.textContent;
                if (text.includes('Student:')) {
                    document.getElementById('email').value = 'student@example.com';
                } else if (text.includes('Teacher:')) {
                    document.getElementById('email').value = 'teacher@example.com';
                }
                document.getElementById('password').value = 'password';
                document.getElementById('password').focus();
            });
        });
    });
</script>
@endsection