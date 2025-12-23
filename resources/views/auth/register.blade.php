<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Student Portal</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- EXTERNAL STYLE SHEET LINKED PROPERLY -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    
    <!-- OR Inline CSS in style tags (better organized than inline HTML) -->
    <style>
        /* ============ CSS VARIABLES ============ */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --error-color: #ef4444;
            --card-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            --transition-default: all 0.3s ease;
        }
        
        /* ============ BASE STYLES ============ */
        .auth-page {
            background: var(--primary-gradient);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        
        /* ============ CARD STYLES ============ */
        .auth-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: var(--card-shadow);
            animation: fadeInUp 0.6s ease-out;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .auth-icon-container {
            width: 5rem;
            height: 5rem;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 1.75rem;
        }
        
        /* ============ FORM STYLES ============ */
        .form-group {
            margin-bottom: 1.75rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.95rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .form-label i {
            color: #9ca3af;
            margin-right: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            font-size: 1rem;
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
        
        /* ============ PASSWORD REQUIREMENTS ============ */
        .password-requirements {
            background: #f9fafb;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-top: 1rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .requirement-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .requirement-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .requirement-item i {
            margin-right: 0.75rem;
        }
        
        .requirement-valid i {
            color: var(--success-color);
        }
        
        .requirement-invalid i {
            color: #f87171;
        }
        
        /* ============ ROLE SELECTION ============ */
        .role-selection {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 0.5rem;
        }
        
        .role-option {
            position: relative;
            cursor: pointer;
        }
        
        .role-option input {
            position: absolute;
            opacity: 0;
        }
        
        .role-card {
            padding: 1.5rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            text-align: center;
            transition: var(--transition-default);
        }
        
        .role-card:hover {
            border-color: #c7d2fe;
            transform: translateY(-2px);
        }
        
        .role-option input:checked + .role-card {
            border-color: var(--primary-color);
            background-color: #eef2ff;
        }
        
        .role-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: #6b7280;
        }
        
        .role-option input:checked + .role-card .role-icon {
            color: var(--primary-color);
        }
        
        /* ============ BUTTON STYLES ============ */
        .auth-button {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            color: white;
            padding: 1.125rem;
            border: none;
            border-radius: 0.75rem;
            font-size: 1.125rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-default);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-button:hover {
            background: linear-gradient(135deg, var(--primary-hover), #7c3aed);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }
        
        .auth-button:active {
            transform: translateY(0);
        }
        
        /* ============ LINK STYLES ============ */
        .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }
        
        .auth-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        
        /* ============ ERROR/SUCCESS MESSAGES ============ */
        .error-message {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .error-message i {
            margin-right: 0.5rem;
        }
        
        .info-message {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 0.875rem;
            border-radius: 0.75rem;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
        
        /* ============ ANIMATIONS ============ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        /* ============ RESPONSIVE STYLES ============ */
        @media (max-width: 640px) {
            .auth-card {
                padding: 1.75rem;
                margin: 1rem;
            }
            
            .role-selection {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .auth-icon-container {
                width: 4rem;
                height: 4rem;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="auth-page">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-2xl mx-auto">
            
            <!-- Auth Card -->
            <div class="auth-card">
                
                <!-- Header -->
                <div class="auth-header">
                    <div class="auth-icon-container">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Your Account</h1>
                    <p class="text-gray-600 text-lg">Join thousands of students and teachers</p>
                </div>
                
                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" id="registrationForm">
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="form-group">
                        <label class="form-label" for="name">
                            <i class="fas fa-user"></i>Full Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                            placeholder="Enter your full name"
                        >
                        @if($errors->has('name'))
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Email Field -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="fas fa-envelope"></i>Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                            placeholder="student@testmail.com"
                        >
                        @if($errors->has('email'))
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        <div class="info-message">
                            <i class="fas fa-info-circle mr-2"></i>
                            Must use your institutional email ending with @testmail.com
                        </div>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="password">
                            <i class="fas fa-lock"></i>Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="Create a strong password"
                            oninput="checkPasswordStrength(this.value)"
                        >
                        @if($errors->has('password'))
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                        
                        <!-- Password Requirements -->
                        <div class="password-requirements">
                            <p class="font-medium text-gray-700 mb-3">Password Requirements:</p>
                            <ul class="requirement-list">
                                <li class="requirement-item" id="req-length">
                                    <i class="fas fa-times requirement-invalid"></i>
                                    <span>At least 8 characters</span>
                                </li>
                                <li class="requirement-item" id="req-lowercase">
                                    <i class="fas fa-times requirement-invalid"></i>
                                    <span>One lowercase letter</span>
                                </li>
                                <li class="requirement-item" id="req-uppercase">
                                    <i class="fas fa-times requirement-invalid"></i>
                                    <span>One uppercase letter</span>
                                </li>
                                <li class="requirement-item" id="req-symbol">
                                    <i class="fas fa-times requirement-invalid"></i>
                                    <span>One special symbol (@$!%*#?&)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">
                            <i class="fas fa-lock"></i>Confirm Password
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="form-input {{ $errors->has('password_confirmation') ? 'error' : '' }}"
                            placeholder="Re-enter your password"
                        >
                        @if($errors->has('password_confirmation'))
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Role Selection -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tag"></i>Account Type
                        </label>
                        <div class="role-selection">
                            <!-- Student -->
                            <label class="role-option">
                                <input 
                                    type="radio" 
                                    name="role" 
                                    value="student" 
                                    {{ old('role') == 'student' ? 'checked' : '' }}
                                    required
                                >
                                <div class="role-card">
                                    <i class="fas fa-graduation-cap role-icon"></i>
                                    <p class="font-medium">Student</p>
                                    <p class="text-sm text-gray-500 mt-1">Access courses & quizzes</p>
                                </div>
                            </label>
                            
                            <!-- Teacher -->
                            <label class="role-option">
                                <input 
                                    type="radio" 
                                    name="role" 
                                    value="teacher" 
                                    {{ old('role') == 'teacher' ? 'checked' : '' }}
                                    required
                                >
                                <div class="role-card">
                                    <i class="fas fa-chalkboard-teacher role-icon"></i>
                                    <p class="font-medium">Teacher</p>
                                    <p class="text-sm text-gray-500 mt-1">Create & manage content</p>
                                </div>
                            </label>
                            
                            <!-- Admin removed-->
                            
                        </div>
                        @if($errors->has('role'))
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $errors->first('role') }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="auth-button">
                        <i class="fas fa-user-plus mr-3"></i>
                        Create Account
                    </button>
                    
                    <!-- Login Link -->
                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="auth-link ml-2">
                                <i class="fas fa-sign-in-alt mr-1"></i>Sign In
                            </a>
                        </p>
                    </div>
                    
                </form>
                
            </div>
            
            <!-- Demo Notice -->
            <div class="mt-6 text-center">
                <p class="text-white/90 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    For demo purposes, only @testmail.com emails are accepted
                </p>
            </div>
            
        </div>
    </div>
    
    <!-- JavaScript (can also be external) -->
    <script>
        function checkPasswordStrength(password) {
            const requirements = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                symbol: /[@$!%*#?&]/.test(password)
            };
            
            // Update visual indicators
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(`req-${req}`);
                const icon = element.querySelector('i');
                
                if (requirements[req]) {
                    icon.className = 'fas fa-check requirement-valid';
                    element.classList.add('requirement-valid');
                    element.classList.remove('requirement-invalid');
                } else {
                    icon.className = 'fas fa-times requirement-invalid';
                    element.classList.add('requirement-invalid');
                    element.classList.remove('requirement-valid');
                }
            });
        }
        
        // Check password on page load if returning with errors
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            if (passwordField && passwordField.value) {
                checkPasswordStrength(passwordField.value);
            }
            
            // Add shake animation to error fields
            document.querySelectorAll('.form-input.error').forEach(input => {
                input.classList.add('shake');
                setTimeout(() => input.classList.remove('shake'), 500);
            });
        });
    </script>
</body>
</html>