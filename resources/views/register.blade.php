@extends('layouts.app')

@section('title', 'Register | Smart Learning Platform')

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
                Create Your Account
            </h2>
            <p class="mt-3 text-gray-600 max-w-md mx-auto">
                Join thousands of students and teachers optimizing their learning experience
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

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="bg-white py-8 px-6 sm:px-10 rounded-2xl shadow-xl border border-gray-100">
            <form class="space-y-6" action="/register" method="POST">
                @csrf
                
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-900">
                        Full Name
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="mt-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="name" name="name" type="text" 
                               autocomplete="name" required
                               value="{{ old('name') }}"
                               class="block w-full pl-10 pr-4 py-3.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200 text-gray-900 text-sm"
                               placeholder="John Doe">
                    </div>
                </div>

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
                    <label for="password" class="block text-sm font-semibold text-gray-900">
                        Password
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
                               class="block w-full pl-10 pr-10 py-3.5 border border-gray-300 rounded-xl placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200 text-gray-900 text-sm"
                               placeholder="••••••••">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Minimum 8 characters with letters and numbers
                    </p>
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-3">
                        Account Type
                        <span class="text-red-600">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Student Card -->
                        <label class="relative">
                            <input type="radio" name="role" value="student" 
                                   class="sr-only peer" 
                                   {{ old('role') == 'student' || !old('role') ? 'checked' : '' }}>
                            <div class="cursor-pointer border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 rounded-xl p-5 transition-all duration-200 hover:border-gray-300 hover:shadow-md peer-checked:shadow-md">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" opacity="0.5"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Student</h3>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Access courses, track progress, and manage your learning schedule
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Recommended
                                    </span>
                                </div>
                            </div>
                        </label>

                        <!-- Teacher Card -->
                        <label class="relative">
                            <input type="radio" name="role" value="teacher" 
                                   class="sr-only peer"
                                   {{ old('role') == 'teacher' ? 'checked' : '' }}>
                            <div class="cursor-pointer border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 rounded-xl p-5 transition-all duration-200 hover:border-gray-300 hover:shadow-md peer-checked:shadow-md">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Teacher</h3>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Create courses, manage content, and track student performance
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-3 block text-sm text-gray-700">
                        I agree to the 
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-800 transition duration-200">Terms of Service</a>
                        and 
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-800 transition duration-200">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0">
                        <span class="flex items-center">
                            Create Account
                            <svg class="ml-2 h-4 w-4 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Already have an account?</span>
                    </div>
                </div>

                <!-- Sign In Link -->
                <div class="mt-6 text-center">
                    <a href="{{ url('/login') }}" 
                       class="inline-flex items-center px-6 py-3 border-2 border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign in to existing account
                    </a>
                </div>
            </div>
        </div>

        <!-- Security Note -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center space-x-2 text-xs text-gray-500 bg-gray-50 px-4 py-2 rounded-full">
                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Your data is secured with 256-bit SSL encryption</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Enhanced form interaction
    document.addEventListener('DOMContentLoaded', function() {
        // Add focus styling to form fields
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
        
        inputs.forEach(input => {
            // Add/remove classes on focus/blur
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
            
            // Real-time validation feedback
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('border-red-300');
                    this.classList.add('border-green-300');
                } else {
                    this.classList.remove('border-green-300');
                    this.classList.add('border-gray-300');
                }
            });
        });
        
        // Role selection animation
        const roleCards = document.querySelectorAll('label[for^="role"]');
        roleCards.forEach(card => {
            card.addEventListener('click', function() {
                roleCards.forEach(c => {
                    c.querySelector('div').classList.remove('scale-105');
                });
                this.querySelector('div').classList.add('scale-105');
            });
        });
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                terms.parentElement.classList.add('animate-pulse');
                setTimeout(() => terms.parentElement.classList.remove('animate-pulse'), 1000);
            }
        });
    });
</script>
@endsection