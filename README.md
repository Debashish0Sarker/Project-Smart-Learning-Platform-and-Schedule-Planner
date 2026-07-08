# Smart Learning Platform and Schedule Planner

This project is a Laravel-based web application for managing courses, quizzes, schedules, notifications, and study progress for both students and teachers.

## What this application does

The platform helps users:

- Register and log in as a student or teacher
- Browse and enroll in courses
- Access course materials and learning resources
- Attempt quizzes and track submissions
- Review weak areas and receive practice quiz support
- Manage course content, quizzes, and grading as a teacher
- Receive notifications and view news updates
- Connect Google Calendar for study and deadline reminders

## Main features

### For students
- Student dashboard with overview and progress information
- Course browsing and enrollment
- Course material viewing and downloading
- Quiz participation and submission tracking
- Weak area analysis and targeted practice quizzes
- Notifications and schedule views

### For teachers
- Teacher dashboard
- Course creation and management
- Quiz creation and management
- Material upload for courses
- Submission review and grading
- Notification management

### Integrations
- Google Calendar connection for event reminders
- News feed integration through News API
- Web push notifications support

## Requirements

Before running the project, make sure you have:

- PHP 8.2 or newer
- Composer
- Node.js and npm
- A database such as MySQL, PostgreSQL, or SQLite

## Installation

1. Clone the repository
   ```bash
   git clone <repository-url>
   cd Project-Smart-Learning-Platform-and-Schedule-Planner
   ```

2. Install PHP dependencies
   ```bash
   composer install
   ```

3. Create your environment file
   ```bash
   copy .env.example .env
   ```
   On Linux or macOS, use:
   ```bash
   cp .env.example .env
   ```

4. Generate the application key
   ```bash
   php artisan key:generate
   ```

5. Configure your database in the .env file
   Example for SQLite:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   ```

   Or use MySQL/PostgreSQL values as needed.

6. Run migrations
   ```bash
   php artisan migrate
   ```

7. Install frontend assets
   ```bash
   npm install
   npm run build
   ```

8. Create the storage link
   ```bash
   php artisan storage:link
   ```

## Running the application

Start the local development server:

```bash
composer run dev
```

This will start the Laravel app, queue listener, logs, and Vite frontend dev server.

You can then open the application in your browser at:

```text
http://127.0.0.1:8000
```

## How to use it

### 1. Create an account
- Open the registration page at /register
- Choose a student or teacher role depending on your purpose
- Log in at /login

### 2. Use the student features
After logging in as a student, visit:

- /student/dashboard
- /student/courses
- /student/quizzes
- /student/weak-areas
- /student/submission-tracker
- /student/notifications

You can enroll in courses, view materials, take quizzes, and review your weak areas.

### 3. Use the teacher features
After logging in as a teacher, visit:

- /teacher/dashboard
- /teacher/courses
- /teacher/quizzes
- /teacher/notifications

Teachers can create courses, upload materials, build quizzes, review submissions, and grade student work.

### 4. Use optional integrations
- Google Calendar: visit /google-calendar
- News page: visit /news
- Notification testing endpoints are available under the student/teacher notification routes

## Environment variables

The app may require the following values in your .env file:

```env
APP_NAME=Smart Learning Platform
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_learning
DB_USERNAME=root
DB_PASSWORD=

NEWS_API_KEY=your_news_api_key_here
```

## Useful commands

```bash
php artisan test
php artisan migrate:fresh --seed
php artisan queue:listen
npm run dev
```

## Notes

- The project is built with Laravel and uses Blade views, Eloquent models, and a Vite-based frontend.
- If frontend assets do not appear correctly, run npm install and npm run build again.
- If you are using a fresh environment, make sure the database is created before running migrations.

# License

This project is intended for academic use and follows the project’s existing licensing setup.
