<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegister() {
        return view('auth.register');
    }

    public function sendResetLink(Request $request)
    {
    // 1. Validate email
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ], [
        'email.exists' => 'No account found with this email.',
    ]);

    // 2. Generate token
    $token = Str::random(64);

    // 3. Save token in password_resets table with 2-min expiry
    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        [
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(), // We enforce 2-min expiry manually later
        ]
    );

    // 4. Prepare reset link (we will make the route in Step 4)
    $resetLink = url('/reset-password/' . $token) . '?email=' . urlencode($request->email);


    // 5. For now: simply show the link on screen (because email sending is Step 5)
    Mail::html(
        "<p>Click the link below to reset your password (valid for 2 minutes):</p>
        <p><a href='{$resetLink}'>Reset Password</a></p>",
       function ($message) use ($request) {
        $message->to($request->email)
                ->subject('Password Reset Request');
    }
    );


    return back()->with('status', 'A password reset link has been sent to your email.');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                // Only allow emails ending with @testmail.com
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@testmail.com')) {
                        $fail('Please use your institutional email (must end with @testmail.com)');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',               // Minimum 8 characters
                'regex:/[a-z]/',       // at least one lowercase
                'regex:/[A-Z]/',       // at least one uppercase
                'regex:/[@$!%*#?&]/',  // at least one symbol
            ],
            'role' => 'required|in:student,teacher,admin',
        ], [
            // Custom error messages
            'email.unique' => 'An account already exists with this email, please use a different email',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one symbol',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect('/login')->with('success', 'Registration successful!');
    }

    public function showLogin() {
        return view('auth.login');
    }
    
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        // Check if user is temporarily blocked
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds."
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Login successful → regenerate session
            $request->session()->regenerate();
            
            // Reset throttle counter
            RateLimiter::clear($throttleKey);

            // Redirect based on role
            $role = Auth::user()->role;
            if ($role === 'student') {
                return redirect('/student/dashboard');
            } elseif ($role === 'teacher') {
                return redirect('/teacher/dashboard');
            } ;
            
        }

        // Login failed → increment throttle counter
        RateLimiter::hit($throttleKey, 300); // 5 minutes = 300 seconds

        return back()->withErrors([
            'email' => 'Invalid email or password.'
        ]);
    }

    public function resetPassword(Request $request)
{
    // 1) Basic validation
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $email = $request->input('email');
    $token = $request->input('token');

    // 2) Find the token record
    $record = DB::table('password_resets')
        ->where('email', $email)
        ->where('token', $token)
        ->first();

    if (! $record) {
        return back()->withErrors(['email' => 'Invalid or expired reset link.']);
    }

    // 3) Check 2-minute expiry
    $created = Carbon::parse($record->created_at);
    if ($created->addMinutes(2)->isPast()) {
        // delete expired token for cleanliness
        DB::table('password_resets')->where('email', $email)->delete();
        return back()->withErrors(['email' => 'Reset link has expired. Please request a new one.']);
    }

    // 4) Update user's password
    $user = User::where('email', $email)->first();
    if (! $user) {
        return back()->withErrors(['email' => 'No user found for this email.']);
    }

    $user->password = Hash::make($request->input('password'));
    $user->save();

    // 5) Remove the used token
    DB::table('password_resets')->where('email', $email)->delete();

    // 6) Redirect to login with success
    return redirect('/login')->with('success', 'Password reset successful! You can now login.');
}


    public function logout() {
        Auth::logout();
        return redirect('/login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }
}
