# Troubleshooting Guide

## Error 419 - Session Mismatch in Student Login (Railway Deployment)

### Problem
After deploying to Railway, students get a **419 (Token Mismatch)** error when logging in, but teachers can log in without issues.

### Root Cause
The issue was caused by using the `database` session driver, which requires a persistent database sessions table. On Railway:
1. Database connections can be unstable or timing out
2. Session reads/writes might fail due to network issues
3. CSRF tokens couldn't be validated properly due to session corruption
4. Cookie-based sessions are more reliable for distributed deployments

### Solution Applied

#### 1. **Session Driver Configuration** (`.env` file)
Changed from database-based sessions to cookie-based sessions:

```dotenv
# BEFORE (caused issues on Railway)
SESSION_DRIVER=database
SESSION_ENCRYPT=false

# AFTER (works reliably on Railway)
SESSION_DRIVER=cookie
SESSION_ENCRYPT=true
```

**Why cookie-based sessions are better for Railway:**
- No database queries needed for session verification
- Tamper-proof with encryption enabled
- Faster CSRF token validation
- More reliable across container restarts

#### 2. **Session Regeneration** (`app/Http/Controllers/Auth/AuthController.php`)
Improved the login method to properly regenerate sessions:

```php
if (Auth::attempt($credentials)) {
    // Regenerate session FIRST (before any other operations)
    $request->session()->regenerate();
    
    // Then clear throttle counter
    RateLimiter::clear($throttleKey);
    
    // Finally redirect
    return redirect('/student/dashboard');
}
```

#### 3. **Middleware Configuration** (`bootstrap/app.php`)
Added proxy trust configuration for Railway:

```php
->withMiddleware(function (Middleware $middleware): void {
    // Trust Railway proxy headers for correct IP detection
    $middleware->trustProxies(at: '*');
})
```

**Why this matters:**
- Railway routes requests through load balancers/proxies
- Without trusting proxies, the app might reject legitimate requests
- Needed for proper CSRF token validation in production

### Testing the Fix

After deploying to Railway:

1. **Test Student Login:**
   ```
   Email: [student-email]@testmail.com
   Password: [password]
   ```
   - Should now succeed with session established
   - No 419 error

2. **Test Teacher Login:**
   ```
   Email: [teacher-email]@testmail.com
   Password: [password]
   ```
   - Should continue to work as before

3. **Verify Session Persistence:**
   - Login as student
   - Navigate to `/student/dashboard`
   - Session should be maintained
   - Can access protected routes without re-login

### Additional Notes

- The `.env.example` file was also updated to reflect the new session configuration
- These changes are backward compatible with local development (database sessions can still be used locally by changing `.env`)
- Cookie encryption is enabled (`SESSION_ENCRYPT=true`) for security
- Session lifetime is set to 120 minutes (configurable via `SESSION_LIFETIME`)

### If Issues Persist

1. **Clear Railway cache:**
   - Redeploy the application
   - Railway might cache old environment variables

2. **Verify environment variables on Railway:**
   - Check that `.env` variables are properly set
   - Ensure `SESSION_DRIVER=cookie` is active

3. **Check browser cookies:**
   - Clear browser cookies/cache
   - Try in private/incognito mode
   - Ensure third-party cookies aren't blocked

4. **Enable debug logging:**
   - Set `APP_DEBUG=true` temporarily
   - Check `/storage/logs/` for detailed error messages
   - Look for CSRF token validation errors

### Files Modified
1. `.env` - Changed session driver from database to cookie
2. `.env.example` - Updated for consistency
3. `app/Http/Controllers/Auth/AuthController.php` - Improved session regeneration
4. `bootstrap/app.php` - Added proxy trust and CSRF middleware configuration

