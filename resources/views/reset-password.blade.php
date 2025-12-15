@extends('layouts.app')

@section('title', 'Reset Password | Smart Learning Platform')

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
                Reset Your Password
            </h2>
            <p class="mt-3 text-gray-600 max-w-md mx-auto">
                Create a new secure password for your account
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
                            Please correct the following errors:
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

        @if(session('status'))
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
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 sm:px-10 rounded-2xl shadow-xl border border-gray-100">
            <form class="space-y-6" action="{{ route('password.store') }}" method="POST">
                @csrf

                <!-- Hidden fields -->
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ request()->query('email') }}">

                <!-- Current email display (read-only) -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div class="text-sm">
                            <div class="font-medium text-gray-700">Reset password for:</div>
                            <div class="text-gray-900 font-semibold mt-1">{{ request()->query('email') }}</div>
                        </div>
                    </div>
                </div>

                <!-- New Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-900">
                        New Password
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="mt-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" 
                               autocomplete="new-password" required
                               class="block w-full pl-10 pr-12 py-3.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200 text-gray-900 text-sm"
                               placeholder="Enter new password">
                        <!-- Eye toggle button -->
                        <button type="button" 
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200">
                            <svg id="eye-icon" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                <circle cx="12" cy="12" r="3"/>
                                <line x1="4" y1="4" x2="20" y2="20" stroke="currentColor" stroke-width="2" id="eye-slash"/>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Must be at least 8 characters with letters and numbers
                    </p>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-900">
                        Confirm New Password
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="mt-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" 
                               autocomplete="new-password" required
                               class="block w-full pl-10 pr-12 py-3.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200 text-gray-900 text-sm"
                               placeholder="Confirm your new password">
                        <!-- Eye toggle button -->
                        <button type="button" 
                                id="toggleConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200">
                            <svg id="eye-confirm-icon" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                <circle cx="12" cy="12" r="3"/>
                                <line x1="4" y1="4" x2="20" y2="20" stroke="currentColor" stroke-width="2" id="eye-confirm-slash"/>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Re-enter your new password to confirm
                    </p>
                </div>

                <!-- Password Requirements -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Password Requirements
                    </h4>
                    <ul class="text-xs text-blue-800 space-y-1">
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Minimum 8 characters
                        </li>
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Include both letters and numbers
                        </li>
                        <li class="flex items-center">
                            <svg class="w-3 h-3 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Passwords must match
                        </li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                        <span class="flex items-center">
                            Reset Password
                            <svg class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition duration-200">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Return to login
                </a>
            </div>
        </div>

        <!-- Security Note -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center space-x-2 text-xs text-gray-500 bg-gray-50 px-4 py-2 rounded-full">
                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Your password is secured with 256-bit encryption</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality for new password field
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeSlash = document.getElementById('eye-slash');
        
        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeSlash.style.display = 'none';
            } else {
                passwordInput.type = 'password';
                eyeSlash.style.display = 'block';
            }
        });
        
        // Password toggle functionality for confirm password field
        const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const eyeConfirmSlash = document.getElementById('eye-confirm-slash');
        
        toggleConfirmBtn.addEventListener('click', function() {
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                eyeConfirmSlash.style.display = 'none';
            } else {
                confirmPasswordInput.type = 'password';
                eyeConfirmSlash.style.display = 'block';
            }
        });
        
        // Real-time password validation feedback
        const newPasswordInput = document.getElementById('password');
        const confirmPasswordInputField = document.getElementById('password_confirmation');
        
        function validatePasswords() {
            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInputField.value;
            
            // Basic validation - you can enhance this
            if (password.length >= 8) {
                newPasswordInput.classList.remove('border-red-300');
                newPasswordInput.classList.add('border-green-300');
            } else if (password.length > 0) {
                newPasswordInput.classList.remove('border-green-300');
                newPasswordInput.classList.add('border-red-300');
            } else {
                newPasswordInput.classList.remove('border-red-300', 'border-green-300');
                newPasswordInput.classList.add('border-gray-300');
            }
            
            // Check if passwords match
            if (confirmPassword.length > 0) {
                if (password === confirmPassword && password.length >= 8) {
                    confirmPasswordInputField.classList.remove('border-red-300');
                    confirmPasswordInputField.classList.add('border-green-300');
                } else if (password !== confirmPassword) {
                    confirmPasswordInputField.classList.remove('border-green-300');
                    confirmPasswordInputField.classList.add('border-red-300');
                }
            } else {
                confirmPasswordInputField.classList.remove('border-red-300', 'border-green-300');
                confirmPasswordInputField.classList.add('border-gray-300');
            }
        }
        
        newPasswordInput.addEventListener('input', validatePasswords);
        confirmPasswordInputField.addEventListener('input', validatePasswords);
        
        // Add focus styling to form fields
        const inputs = document.querySelectorAll('input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
        });
        
        // Auto-focus new password field
        document.getElementById('password')?.focus();
    });
</script>
@endsection