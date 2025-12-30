<!-- STUDENT PORTAL ISOLATED NAVIGATION -->
<!-- Save as: resources/views/components/student-isolated-navigation.blade.php -->
<!-- Or as standalone HTML file -->

<!-- Alpine.js for mobile menu (load once in head) -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Navigation Container - ISOLATED with unique classes -->
<nav x-data="{ spNavOpen: false }" class="student-portal-nav" style="position: relative; z-index: 1000;">
    <!-- Inline Scoped Styles for Navigation Only -->
    <style>
        /* ISOLATED: Only affects .student-portal-nav and children */
        .student-portal-nav {
            font-family: 'Inter', sans-serif;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .student-portal-nav * {
            box-sizing: border-box;
        }
        
        .student-portal-nav .sp-nav-container {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (min-width: 640px) {
            .student-portal-nav .sp-nav-container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
        
        @media (min-width: 1024px) {
            .student-portal-nav .sp-nav-container {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
        
        .student-portal-nav .sp-nav-inner {
            display: flex;
            justify-content: space-between;
            height: 4rem;
        }
        
        .student-portal-nav .sp-nav-left {
            display: flex;
        }
        
        .student-portal-nav .sp-nav-logo-container {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }
        
        .student-portal-nav .sp-nav-logo-link {
            display: inline-block;
        }
        
        .student-portal-nav .sp-nav-logo {
            display: block;
            height: 2.25rem;
            width: auto;
            fill: #1f2937;
        }
        
        .student-portal-nav .sp-desktop-links {
            display: none;
            align-items: center;
            margin-left: 2.5rem;
        }
        
        @media (min-width: 640px) {
            .student-portal-nav .sp-desktop-links {
                display: flex;
            }
        }
        
        .student-portal-nav .sp-desktop-links a {
            margin-left: 2rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: color 0.15s ease-in-out;
        }
        
        .student-portal-nav .sp-desktop-links a:hover {
            color: #374151;
        }
        
        .student-portal-nav .sp-desktop-links a.sp-nav-active {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }
        
        .student-portal-nav .sp-nav-right {
            display: none;
            align-items: center;
            margin-left: 1.5rem;
        }
        
        @media (min-width: 640px) {
            .student-portal-nav .sp-nav-right {
                display: flex;
            }
        }
        
        .student-portal-nav .sp-dropdown-trigger {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            background-color: white;
            transition: all 0.15s ease-in-out;
        }
        
        .student-portal-nav .sp-dropdown-trigger:hover {
            color: #374151;
        }
        
        .student-portal-nav .sp-dropdown-chevron {
            margin-left: 0.25rem;
            height: 1rem;
            width: 1rem;
            fill: currentColor;
        }
        
        .student-portal-nav .sp-dropdown-content {
            position: absolute;
            right: 0;
            margin-top: 0.5rem;
            width: 12rem;
            background-color: white;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 0;
            border: 1px solid #e5e7eb;
            z-index: 10;
        }
        
        .student-portal-nav .sp-dropdown-link {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            text-align: left;
            color: #374151;
            text-decoration: none;
            transition: background-color 0.15s ease-in-out;
        }
        
        .student-portal-nav .sp-dropdown-link:hover {
            background-color: #f9fafb;
        }
        
        .student-portal-nav .sp-mobile-menu {
            display: flex;
            align-items: center;
            margin-right: -0.5rem;
        }
        
        @media (min-width: 640px) {
            .student-portal-nav .sp-mobile-menu {
                display: none;
            }
        }
        
        .student-portal-nav .sp-hamburger-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem;
            border-radius: 0.375rem;
            color: #9ca3af;
            transition: all 0.15s ease-in-out;
        }
        
        .student-portal-nav .sp-hamburger-button:hover {
            color: #6b7280;
            background-color: #f9fafb;
        }
        
        .student-portal-nav .sp-hamburger-icon {
            height: 1.5rem;
            width: 1.5rem;
            stroke: currentColor;
            fill: none;
        }
        
        .student-portal-nav .sp-mobile-panel {
            display: none;
        }
        
        .student-portal-nav .sp-mobile-panel.sp-nav-open {
            display: block;
        }
        
        .student-portal-nav .sp-mobile-links {
            padding-top: 0.5rem;
            padding-bottom: 0.75rem;
        }
        
        .student-portal-nav .sp-mobile-link {
            display: block;
            padding: 0.5rem 1rem;
            color: #374151;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.15s ease-in-out;
        }
        
        .student-portal-nav .sp-mobile-link:hover {
            background-color: #f9fafb;
        }
        
        .student-portal-nav .sp-mobile-link.sp-nav-active {
            color: #4f46e5;
        }
        
        .student-portal-nav .sp-mobile-user-section {
            padding-top: 1rem;
            padding-bottom: 0.25rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .student-portal-nav .sp-mobile-user-info {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .student-portal-nav .sp-mobile-user-name {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .student-portal-nav .sp-mobile-user-email {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
        }
        
        /* Logout form */
        .student-portal-nav .sp-logout-form {
            display: inline;
        }
        
        .student-portal-nav .sp-logout-button {
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            color: inherit;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }
        
        /* Notification bell styles */
        .student-portal-nav .sp-notification-bell {
            position: relative;
            margin-right: 1rem;
        }
        
        .student-portal-nav .sp-notification-icon {
            color: #6b7280;
            transition: color 0.15s ease-in-out;
        }
        
        .student-portal-nav .sp-notification-icon:hover {
            color: #374151;
        }
        
        .student-portal-nav .sp-notification-badge {
            position: absolute;
            top: -0.25rem;
            right: -0.25rem;
            height: 0.75rem;
            width: 0.75rem;
            background-color: #ef4444;
            border-radius: 9999px;
            border: 2px solid white;
        }
        
        /* Add space for fixed nav on body */
        body {
            padding-top: 4rem !important;
        }
    </style>

    <!-- Primary Navigation Menu -->
    <div class="sp-nav-container">
        <div class="sp-nav-inner">
            <!-- Left Section -->
            <div class="sp-nav-left">
                <!-- Logo -->
                <div class="sp-nav-logo-container">
                    <a href="/dashboard" class="sp-nav-logo-link">
                        <!-- Replace with your logo - using simple text for now -->
                        <div class="sp-nav-logo" style="font-weight: bold; font-size: 1.5rem; color: #4f46e5;">
                            EduPortal
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="sp-desktop-links">
                    <a href="/dashboard" class="sp-nav-active">Dashboard</a>
                    <a href="/student/weak-areas">Weak Areas</a>
                    <a href="/student/practice-quiz/create">Practice Quiz</a>
                    <a href="/student/submission-tracker">Submission Tracker</a>
                </div>
            </div>

            <!-- Right Section (Desktop) -->
            <div class="sp-nav-right">
                <!-- Notification Bell -->
                <div class="sp-notification-bell">
                    <a href="#" class="sp-notification-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="sp-notification-badge"></span>
                    </a>
                </div>
                
                <!-- User Dropdown -->
                <div class="sp-dropdown-container" x-data="{ spDropdownOpen: false }" @click.outside="spDropdownOpen = false">
                    <button @click="spDropdownOpen = !spDropdownOpen" class="sp-dropdown-trigger">
                        <span>Student Name</span>
                        <svg class="sp-dropdown-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <div x-show="spDropdownOpen" x-transition class="sp-dropdown-content">
                        <form method="POST" action="/logout" class="sp-logout-form">
                            <input type="hidden" name="_token" value="">
                            <button type="submit" class="sp-dropdown-link sp-logout-button">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="sp-mobile-menu">
                <!-- Mobile Notification Bell -->
                <div class="sp-notification-bell">
                    <a href="#" class="sp-notification-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="sp-notification-badge"></span>
                    </a>
                </div>
                
                <button @click="spNavOpen = !spNavOpen" class="sp-hamburger-button">
                    <svg class="sp-hamburger-icon" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': spNavOpen, 'inline-flex': !spNavOpen}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !spNavOpen, 'inline-flex': spNavOpen}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'sp-nav-open': spNavOpen}" class="sp-mobile-panel">
        <div class="sp-mobile-links">
            <a href="/dashboard" class="sp-mobile-link sp-nav-active">Dashboard</a>
            <a href="/student/weak-areas" class="sp-mobile-link">Weak Areas</a>
            <a href="/student/practice-quiz/create" class="sp-mobile-link">Practice Quiz</a>
            <a href="/student/submission-tracker" class="sp-mobile-link">Submission Tracker</a>
        </div>

        <!-- Mobile User Section -->
        <div class="sp-mobile-user-section">
            <div class="sp-mobile-user-info">
                <div class="sp-mobile-user-name">Student Name</div>
                <div class="sp-mobile-user-email">student@example.com</div>
            </div>

            <div class="mt-3">
                <form method="POST" action="/logout" class="sp-logout-form">
                    <input type="hidden" name="_token" value="">
                    <button type="submit" class="sp-mobile-link sp-logout-button">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
<!-- END ISOLATED NAVIGATION -->