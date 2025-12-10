# clean-merge.ps1
cd C:\xampp\htdocs\smart_learning_app\Project-Smart-Learning-Platform-and-Schedule-Planner

Write-Host "=== CLEAN LARAVEL MERGE ===" -ForegroundColor Magenta
Write-Host "Keeping friend's tables, adding only your unique tables" -ForegroundColor Cyan

# 1. Backup
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupDir = "..\merge-backup-$timestamp"
Copy-Item . $backupDir -Recurse -Exclude vendor, node_modules, storage
Write-Host "1. Backup created: $backupDir" -ForegroundColor Green

# 2. Delete ALL migrations from 2025_12_07 (conflict day)
Write-Host "`n2. Removing conflicting migrations..." -ForegroundColor Yellow
Get-ChildItem database\migrations\2025_12_07_*.php 2>$null | Remove-Item -Force
Write-Host "   Removed all 2025-12-07 migrations" -ForegroundColor Green

# 3. Find your unique tables
Write-Host "`n3. Finding your unique tables..." -ForegroundColor Cyan
$yourPath = "..\smart-learning-platform\database\migrations"
$repoPath = "database\migrations"

$yourFiles = @(Get-ChildItem "$yourPath\*.php" -Exclude "0001_01_01*")
$repoFiles = @(Get-ChildItem "$repoPath\*.php" -Exclude "0001_01_01*")

$uniqueCount = 0
foreach ($file in $yourFiles) {
    $fileName = $file.Name -replace '^\d{4}_\d{2}_\d{2}_\d{6}_', ''
    
    # Check if similar file exists in repo
    $exists = $repoFiles | Where-Object { $_.Name -replace '^\d{4}_\d{2}_\d{2}_\d{6}_', '' -eq $fileName }
    
    if (-not $exists) {
        $uniqueCount++
        $newTimestamp = Get-Date -Format "yyyy_MM_dd_HHmmss"
        $counter = ($uniqueCount - 1).ToString("00")
        $newName = "${newTimestamp}${counter}_$fileName"
        
        Copy-Item $file.FullName "$repoPath\$newName"
        Write-Host "   + $fileName" -ForegroundColor Green
    }
}

if ($uniqueCount -eq 0) {
    Write-Host "   No unique tables found from your project" -ForegroundColor Yellow
}

# 4. Merge other files (excluding migrations already handled)
Write-Host "`n4. Merging other files..." -ForegroundColor Cyan
$exclude = @('vendor', 'node_modules', '.env', '.git', 'storage', 'composer.lock', 'package-lock.json', 'database\migrations')

robocopy "..\smart-learning-platform" . /E /XO /XN /XC /NFL /NDL /NJH /NJS /XD vendor node_modules .git storage /XF .env composer.lock package-lock.json

Write-Host "   Files merged!" -ForegroundColor Green

# 5. Database
Write-Host "`n5. Setting up database..." -ForegroundColor Cyan
php artisan migrate:fresh --force
Write-Host "   Database ready!" -ForegroundColor Green

# 6. Dependencies
Write-Host "`n6. Installing dependencies..." -ForegroundColor Cyan
composer install --no-interaction
npm install --silent
Write-Host "   Dependencies installed!" -ForegroundColor Green

# 7. Cleanup
Write-Host "`n7. Cleaning caches..." -ForegroundColor Cyan
php artisan config:clear
php artisan cache:clear
php artisan view:clear
Write-Host "   Caches cleared!" -ForegroundColor Green

# 8. Final
Write-Host "`n========================================" -ForegroundColor Green
Write-Host "✅ MERGE COMPLETE!" -ForegroundColor Green
Write-Host "========================================`n" -ForegroundColor Green

Write-Host "Summary:" -ForegroundColor Yellow
Write-Host "- Kept friend's tables (courses, quizzes, questions, enrollments, materials)" -ForegroundColor White
Write-Host "- Added $uniqueCount unique tables from your project" -ForegroundColor White
Write-Host "- Database was reset (test data lost)" -ForegroundColor White

Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "1. Test: php artisan serve" -ForegroundColor White
Write-Host "2. Add seeders for test data" -ForegroundColor White
Write-Host "3. Git: git add . && git commit -m 'Clean merge'" -ForegroundColor White

Write-Host "`nBackup: $backupDir" -ForegroundColor Cyan