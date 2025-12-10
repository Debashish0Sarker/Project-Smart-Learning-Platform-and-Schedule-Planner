# merge-feature3-fixed.ps1 - Fixed version with proper escaping
param(
    [string]$SourcePath = "C:\xampp\htdocs\smart_learning_app\smart-learning-platform",
    [string]$DestPath = (Get-Location).Path,
    [switch]$Backup = $true,
    [switch]$DryRun = $false
)

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "FEATURE 3: SMART FILE MERGER (FIXED)" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Source: $SourcePath" -ForegroundColor Yellow
Write-Host "Destination: $DestPath" -ForegroundColor Yellow
Write-Host "Dry Run: $DryRun" -ForegroundColor Yellow
Write-Host "=========================================" -ForegroundColor Cyan

# Files to copy (overwrite if exists)
$copyFiles = @(
    "app\Http\Controllers\Teacher\QuizController.php",
    "app\Models\Quiz.php",
    "app\Models\Question.php",
    "app\Models\Enrollment.php",
    "app\Models\CourseMaterial.php",
    "database\migrations\",
    "database\seeders\",
    "resources\views\teacher\",
    "resources\views\layouts\teacher.blade.php",
    "resources\views\student\dashboard.blade.php"
)

# Files that need manual review/merging
$mergeFiles = @(
    "routes\web.php",
    "app\Models\User.php",
    "app\Models\Course.php",
    ".env.example",
    "composer.json",
    "package.json"
)

# Directories to ensure exist
$directories = @(
    "app\Http\Controllers\Teacher",
    "app\Models",
    "database\migrations",
    "database\seeders",
    "resources\views\teacher",
    "resources\views\teacher\quizzes",
    "resources\views\teacher\courses",
    "resources\views\layouts",
    "resources\views\student"
)

# Simple file copy function
function Copy-FileSafe {
    param($source, $destination)
    
    $sourceFull = Join-Path $SourcePath $source
    $destFull = Join-Path $DestPath $destination
    
    if (Test-Path $sourceFull) {
        if ($DryRun) {
            Write-Host "[DRY RUN] Would copy: $source -> $destination" -ForegroundColor Gray
        }
        else {
            # Create directory if it doesn't exist
            $destDir = Split-Path $destFull -Parent
            if (-not (Test-Path $destDir)) {
                New-Item -ItemType Directory -Path $destDir -Force | Out-Null
            }
            
            # Backup if exists
            if (Test-Path $destFull) {
                $backupPath = "$destFull.backup_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
                Copy-Item $destFull $backupPath -Force
                Write-Host "  Backed up: $backupPath" -ForegroundColor DarkGray
            }
            
            Copy-Item $sourceFull $destFull -Force
            Write-Host "  Copied: $destination" -ForegroundColor Green
        }
        return $true
    }
    else {
        Write-Host "  Source not found: $source" -ForegroundColor Yellow
        return $false
    }
}

# Copy directory recursively
function Copy-DirectorySafe {
    param($source, $destination)
    
    $sourceFull = Join-Path $SourcePath $source
    $destFull = Join-Path $DestPath $destination
    
    if (Test-Path $sourceFull) {
        if ($DryRun) {
            Write-Host "[DRY RUN] Would copy directory: $source -> $destination" -ForegroundColor Gray
        }
        else {
            # Create destination directory
            if (-not (Test-Path $destFull)) {
                New-Item -ItemType Directory -Path $destFull -Force | Out-Null
            }
            
            # Copy all files
            Get-ChildItem -Path $sourceFull -Recurse | ForEach-Object {
                $relativePath = $_.FullName.Substring($sourceFull.Length + 1)
                $destPath = Join-Path $destFull $relativePath
                
                # Create subdirectory if needed
                $destDir = Split-Path $destPath -Parent
                if (-not (Test-Path $destDir)) {
                    New-Item -ItemType Directory -Path $destDir -Force | Out-Null
                }
                
                Copy-Item $_.FullName $destPath -Force
            }
            Write-Host "  Copied directory: $source" -ForegroundColor Green
        }
        return $true
    }
    else {
        Write-Host "  Source directory not found: $source" -ForegroundColor Yellow
        return $false
    }
}

# Main execution
try {
    # Create directories
    Write-Host "`nCreating directories..." -ForegroundColor Blue
    foreach ($dir in $directories) {
        $fullDir = Join-Path $DestPath $dir
        if (-not (Test-Path $fullDir)) {
            if ($DryRun) {
                Write-Host "[DRY RUN] Would create directory: $dir" -ForegroundColor Gray
            }
            else {
                New-Item -ItemType Directory -Path $fullDir -Force | Out-Null
                Write-Host "  Created: $dir" -ForegroundColor Green
            }
        }
    }
    
    # Copy files
    Write-Host "`nCopying files..." -ForegroundColor Blue
    foreach ($item in $copyFiles) {
        if ($item.EndsWith("\")) {
            # It's a directory
            Copy-DirectorySafe -source $item -destination $item
        }
        else {
            # It's a file
            Copy-FileSafe -source $item -destination $item
        }
    }
    
    # Handle merge files (create manual merge instructions)
    Write-Host "`nFiles that need MANUAL MERGING:" -ForegroundColor Magenta
    foreach ($file in $mergeFiles) {
        $sourceFile = Join-Path $SourcePath $file
        $destFile = Join-Path $DestPath $file
        
        if (Test-Path $sourceFile) {
            if (Test-Path $destFile) {
                Write-Host "  MERGE NEEDED: $file" -ForegroundColor Yellow
                Write-Host "    Source: $sourceFile" -ForegroundColor Gray
                Write-Host "    Destination: $destFile" -ForegroundColor Gray
                
                if (-not $DryRun) {
                    # Create a .merge file with instructions
                    $mergeGuide = "$destFile.merge_guide.txt"
                    $guideContent = @"
FILE: $file
SOURCE: $sourceFile
DESTINATION: $destFile
DATE: $(Get-Date)

=== INSTRUCTIONS ===
1. Open BOTH files in a text editor
2. Compare the differences
3. Manually copy needed changes from source to destination
4. Save the destination file

=== QUICK MERGE TIPS ===

For routes/web.php:
- Add teacher routes group at the end of the file

For app/Models/User.php:
- Add these methods if missing:
  * coursesTeaching()
  * quizzes() 
  * questions()
  * enrollments()
  * enrolledCourses()
  * isTeacher(), isStudent(), isAdmin()

For app/Models/Course.php:
- Add relationships to Quiz and Enrollment models

For .env.example:
- Add any missing environment variables

For composer.json/package.json:
- Add any missing dependencies
"@
                    Set-Content -Path $mergeGuide -Value $guideContent -Encoding UTF8
                    Write-Host "    Created merge guide: $mergeGuide" -ForegroundColor Cyan
                }
            }
            else {
                Write-Host "  COPY (new file): $file" -ForegroundColor Green
                Copy-FileSafe -source $file -destination $file
            }
        }
    }
    
    # Create simple routes merge if web.php doesn't exist in destination
    $routesSource = Join-Path $SourcePath "routes\web.php"
    $routesDest = Join-Path $DestPath "routes\web.php"
    
    if ((Test-Path $routesSource) -and -not (Test-Path $routesDest) -and -not $DryRun) {
        Copy-FileSafe -source "routes\web.php" -destination "routes\web.php"
        Write-Host "  Created new routes file" -ForegroundColor Green
    }
    
    # Create README
    Write-Host "`nCreating documentation..." -ForegroundColor Blue
    $readmePath = Join-Path $DestPath "FEATURE3_SETUP.md"
    if (-not $DryRun) {
        $readmeContent = @"
# Feature 3 Setup Instructions

## Files Copied:
- Controllers: app/Http/Controllers/Teacher/
- Models: Quiz.php, Question.php, Enrollment.php, CourseMaterial.php
- Views: resources/views/teacher/
- Migrations: database/migrations/
- Seeders: database/seeders/

## Files Requiring Manual Merge:
$(($mergeFiles | ForEach-Object { "- $_" }) -join "`n")

## Quick Setup:
1. Run migrations: \`php artisan migrate\`
2. Test: \`php artisan serve\`
3. Visit: http://localhost:8000/teacher/quizzes/create

## Manual Merge Required For:
1. **routes/web.php** - Add teacher routes section
2. **app/Models/User.php** - Add teacher/student relationship methods
3. **Other files** - See individual .merge_guide.txt files

## Testing:
- Teacher dashboard: /teacher/dashboard
- Quiz creation: /teacher/quizzes/create
- Quiz list: /teacher/quizzes
"@
        Set-Content -Path $readmePath -Value $readmeContent -Encoding UTF8
        Write-Host "  Created: FEATURE3_SETUP.md" -ForegroundColor Green
    }
    
    # Summary
    Write-Host "`n=========================================" -ForegroundColor Cyan
    Write-Host "SUMMARY" -ForegroundColor Cyan
    Write-Host "=========================================" -ForegroundColor Cyan
    
    if ($DryRun) {
        Write-Host "DRY RUN COMPLETE" -ForegroundColor Yellow
        Write-Host "Review the output above, then run without -DryRun" -ForegroundColor Yellow
    }
    else {
        Write-Host "FILE COPY COMPLETE!" -ForegroundColor Green
        Write-Host "`nNEXT STEPS:" -ForegroundColor Yellow
        Write-Host "1. Check files with .merge_guide.txt for manual merging" -ForegroundColor White
        Write-Host "2. Run migrations: php artisan migrate" -ForegroundColor White
        Write-Host "3. Test: php artisan serve" -ForegroundColor White
        Write-Host "4. Visit: http://localhost:8000/teacher/quizzes/create" -ForegroundColor White
        Write-Host "`nIMPORTANT: Some files need manual merging (see list above)" -ForegroundColor Magenta
    }
    
    Write-Host "=========================================" -ForegroundColor Cyan
    
}
catch {
    Write-Host "`nERROR: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}