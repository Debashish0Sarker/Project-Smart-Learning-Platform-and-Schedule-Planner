# smart-merge.ps1
param(
    [string]$FeatureName = "feature3"  # Change this to your feature name
)

# ================= CONFIGURATION =================
$SourceDir = "C:\xampp\htdocs\smart_learning_app\smart-learning-platform"
$DestDir = "C:\xampp\htdocs\smart_learning_app\Project-Smart-Learning-Platform-and-Schedule-Planner"
$BackupDir = "$DestDir-backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')"

# Files to ALWAYS SKIP (case-sensitive)
$ExcludeFiles = @('.env', '.git', 'node_modules', 'vendor', 'storage', 'composer.lock', 'package-lock.json')
# Files to MERGE WITH CAUTION (will be backed up first)
$CriticalFiles = @('composer.json', 'package.json', 'routes/web.php', 'routes/api.php')

# ================= FUNCTIONS =================
function Write-Info($message) { Write-Host "[INFO] $message" -ForegroundColor Cyan }
function Write-Success($message) { Write-Host "[SUCCESS] $message" -ForegroundColor Green }
function Write-Warning($message) { Write-Host "[WARNING] $message" -ForegroundColor Yellow }
function Write-Error($message) { Write-Host "[ERROR] $message" -ForegroundColor Red }

# ================= MAIN SCRIPT =================
Write-Host "=========================================" -ForegroundColor Magenta
Write-Host "   SMART LARAVEL PROJECT MERGER" -ForegroundColor Magenta
Write-Host "=========================================`n" -ForegroundColor Magenta

# STEP 1: Backup destination
Write-Info "Step 1: Creating backup of repository..."
New-Item -ItemType Directory -Path $BackupDir -Force | Out-Null
Copy-Item -Path "$DestDir\*" -Destination $BackupDir -Recurse -Exclude $ExcludeFiles
Write-Success "Backup created at: $BackupDir"

# STEP 2: Detect what's different
Write-Info "`nStep 2: Analyzing differences..."
$Differences = @()

# Get all files from source (your project)
$AllSourceFiles = Get-ChildItem -Path $SourceDir -Recurse -File | 
Where-Object {
    $exclude = $false
    foreach ($ex in $ExcludeFiles) {
        if ($_.FullName -match [regex]::Escape($ex)) {
            $exclude = $true
            break
        }
    }
    -not $exclude
}

foreach ($file in $AllSourceFiles) {
    $relativePath = $file.FullName.Substring($SourceDir.Length + 1)
    $destPath = Join-Path -Path $DestDir -ChildPath $relativePath
    
    if (-not (Test-Path -Path $destPath)) {
        # New file
        $Differences += [PSCustomObject]@{
            Type   = "NEW"
            Path   = $relativePath
            Action = "Copy"
        }
    }
    else {
        # Compare file content
        $sourceHash = (Get-FileHash $file.FullName -Algorithm MD5).Hash
        $destHash = (Get-FileHash $destPath -Algorithm MD5).Hash
        
        if ($sourceHash -ne $destHash) {
            if ($relativePath -in $CriticalFiles) {
                $Differences += [PSCustomObject]@{
                    Type   = "CONFLICT"
                    Path   = $relativePath
                    Action = "Manual Review"
                }
            }
            else {
                $Differences += [PSCustomObject]@{
                    Type   = "DIFFERENT"
                    Path   = $relativePath
                    Action = "Overwrite"
                }
            }
        }
    }
}

# Display differences
Write-Info "Found $($Differences.Count) differences:"
$Differences | Group-Object Type | ForEach-Object {
    Write-Host "  $($_.Name): $($_.Count) files" -ForegroundColor White
}

# Show top 10 differences
Write-Info "`nTop differences to merge:"
$Differences | Where-Object { $_.Type -in @("NEW", "DIFFERENT") } | 
Select-Object -First 10 Type, Path | Format-Table -AutoSize

# STEP 3: Smart merge by directory
Write-Info "`nStep 3: Smart merging..."

# Merge by priority (most important first)
$MergePriority = @(
    @{Name = "Database Migrations"; Source = "$SourceDir\database\migrations\*"; Dest = "$DestDir\database\migrations"; Pattern = "*$FeatureName*" },
    @{Name = "Controllers"; Source = "$SourceDir\app\Http\Controllers\*"; Dest = "$DestDir\app\Http\Controllers"; Pattern = "*.php" },
    @{Name = "Models"; Source = "$SourceDir\app\Models\*"; Dest = "$DestDir\app\Models"; Pattern = "*.php" },
    @{Name = "Views"; Source = "$SourceDir\resources\views\*"; Dest = "$DestDir\resources\views"; Pattern = "*" },
    @{Name = "Routes"; Source = "$SourceDir\routes\*"; Dest = "$DestDir\routes"; Pattern = "*.php" },
    @{Name = "Config"; Source = "$SourceDir\config\*"; Dest = "$DestDir\config"; Pattern = "*.php" },
    @{Name = "Assets"; Source = "$SourceDir\public\*"; Dest = "$DestDir\public"; Pattern = "*" }
)

foreach ($item in $MergePriority) {
    if (Test-Path $item.Source) {
        Write-Info "  Merging $($item.Name)..."
        
        # Create destination if doesn't exist
        if (-not (Test-Path $item.Dest)) {
            New-Item -ItemType Directory -Path $item.Dest -Force | Out-Null
        }
        
        # Copy files
        $files = Get-ChildItem -Path $item.Source -Include $item.Pattern -Recurse -File
        foreach ($file in $files) {
            $relativePath = $file.FullName.Substring($SourceDir.Length + 1)
            $destPath = Join-Path -Path $DestDir -ChildPath $relativePath
            
            # Create directory structure
            $destDir = Split-Path -Path $destPath -Parent
            if (-not (Test-Path -Path $destDir)) {
                New-Item -ItemType Directory -Path $destDir -Force | Out-Null
            }
            
            Copy-Item -Path $file.FullName -Destination $destPath -Force
        }
        Write-Success "    Done! Copied $($files.Count) files"
    }
}

# STEP 4: Handle special files
Write-Info "`nStep 4: Handling special files..."

# Merge composer.json intelligently
if (Test-Path "$SourceDir\composer.json" -and Test-Path "$DestDir\composer.json") {
    Write-Info "  Checking composer.json dependencies..."
    
    $sourceComposer = Get-Content "$SourceDir\composer.json" | ConvertFrom-Json
    $destComposer = Get-Content "$DestDir\composer.json" | ConvertFrom-Json
    
    # Compare require sections
    $newDeps = @{}
    if ($sourceComposer.require) {
        foreach ($dep in $sourceComposer.require.PSObject.Properties) {
            if (-not $destComposer.require.$($dep.Name)) {
                $newDeps[$dep.Name] = $dep.Value
                Write-Host "    + $($dep.Name): $($dep.Value)" -ForegroundColor Green
            }
        }
    }
    
    if ($newDeps.Count -gt 0) {
        $addDeps = Read-Host "`n  Add these dependencies to composer.json? (y/n)"
        if ($addDeps -eq 'y') {
            foreach ($dep in $newDeps.GetEnumerator()) {
                $destComposer.require | Add-Member -MemberType NoteProperty -Name $dep.Key -Value $dep.Value -Force
            }
            $destComposer | ConvertTo-Json -Depth 10 | Set-Content "$DestDir\composer.json"
            Write-Success "    Updated composer.json"
        }
    }
}

# Merge routes intelligently
if (Test-Path "$SourceDir\routes\web.php") {
    Write-Info "`n  Your new routes in web.php:"
    $yourRoutes = Select-String -Path "$SourceDir\routes\web.php" -Pattern "Route::"
    if ($yourRoutes) {
        $yourRoutes | ForEach-Object { Write-Host "    $($_.Line.Trim())" -ForegroundColor Cyan }
        Write-Warning "  Please manually add these routes to $DestDir\routes\web.php"
    }
}

# STEP 5: Database migration helper
Write-Info "`nStep 5: Database migration assistance..."

# Find your migration files
$yourMigrations = Get-ChildItem "$SourceDir\database\migrations\*.php" | 
Where-Object { $_.Name -match $FeatureName } |
Select-Object Name, @{Name = "SizeKB"; Expression = { [math]::Round($_.Length / 1KB, 2) } }

if ($yourMigrations) {
    Write-Info "  Your migration files:"
    $yourMigrations | Format-Table -AutoSize
    
    # Copy them with new timestamps to avoid conflicts
    $latestMigration = Get-ChildItem "$DestDir\database\migrations\*.php" | 
    Sort-Object Name -Descending | 
    Select-Object -First 1
    
    if ($latestMigration) {
        $baseTimestamp = $latestMigration.Name.Substring(0, 17)
        $counter = [int]$baseTimestamp.Substring(14, 2)
        
        foreach ($migration in $yourMigrations) {
            $counter++
            $newTimestamp = (Get-Date).ToString("yyyy_MM_dd") + "_" + $counter.ToString("000000")
            $newName = $migration.Name -replace '^\d{4}_\d{2}_\d{2}_\d{6}', $newTimestamp
            Copy-Item "$SourceDir\database\migrations\$($migration.Name)" "$DestDir\database\migrations\$newName"
            Write-Host "    Copied as: $newName" -ForegroundColor Green
        }
    }
}
else {
    Write-Info "  No migration files found for feature: $FeatureName"
}

# STEP 6: Post-merge instructions
Write-Host "`n=========================================" -ForegroundColor Magenta
Write-Host "   MERGE COMPLETE - NEXT STEPS" -ForegroundColor Magenta
Write-Host "=========================================`n" -ForegroundColor Magenta

Write-Success "1. Run these commands in order:"
Write-Host "   cd '$DestDir'" -ForegroundColor White
Write-Host "   composer install" -ForegroundColor White
Write-Host "   npm install" -ForegroundColor White
Write-Host "   php artisan migrate" -ForegroundColor White
Write-Host "   php artisan config:clear" -ForegroundColor White
Write-Host "   php artisan cache:clear" -ForegroundColor White

Write-Success "`n2. Manual tasks required:"
Write-Host "   - Check routes/web.php for duplicates" -ForegroundColor Yellow
Write-Host "   - Merge .env variables manually" -ForegroundColor Yellow
Write-Host "   - Test your feature thoroughly" -ForegroundColor Yellow

Write-Success "`n3. Git commands:"
Write-Host "   git add ." -ForegroundColor White
Write-Host "   git commit -m 'Add $FeatureName feature'" -ForegroundColor White
Write-Host "   git push origin feature/$FeatureName" -ForegroundColor White

Write-Host "`n⚠️  Backup location: $BackupDir" -ForegroundColor Cyan
Write-Host "   If anything breaks, restore from this backup.`n" -ForegroundColor Cyan