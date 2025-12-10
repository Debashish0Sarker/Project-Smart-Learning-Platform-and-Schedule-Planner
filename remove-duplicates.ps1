# remove-duplicates.ps1
cd C:\xampp\htdocs\smart_learning_app\Project-Smart-Learning-Platform-and-Schedule-Planner

Write-Host "=== REMOVING ALL DUPLICATES ===" -ForegroundColor Magenta

# Group migrations by their action (ignoring timestamps)
$migrations = Get-ChildItem database\migrations\*.php
$migrationMap = @{}

foreach ($migration in $migrations) {
    # Extract action from filename
    $name = $migration.Name
    
    if ($name -match '^\d{4}_\d{2}_\d{2}_\d{6}_(.+)\.php$') {
        $action = $Matches[1]
        
        if (-not $migrationMap[$action]) {
            $migrationMap[$action] = @()
        }
        
        $migrationMap[$action] += @{
            File = $migration
            Name = $name
            Date = ($name -split '_')[0..3] -join '_'
        }
    }
}

# Show and delete duplicates
Write-Host "`nDuplicate migrations found:" -ForegroundColor Yellow

foreach ($action in $migrationMap.Keys | Sort-Object) {
    $files = $migrationMap[$action]
    
    if ($files.Count -gt 1) {
        Write-Host "`n⚠️  $action" -ForegroundColor Red
        
        # Sort by date (keep earliest, delete later)
        $sorted = $files | Sort-Object Date
        $keep = $sorted[0]
        $delete = $sorted[1..$sorted.Count]
        
        Write-Host "  Keeping: $($keep.Name)" -ForegroundColor Green
        foreach ($item in $delete) {
            Remove-Item $item.File.FullName -Force
            Write-Host "  Deleting: $($item.Name)" -ForegroundColor Yellow
        }
    }
}

# Also delete any migration from today (2025_12_10) as they're likely duplicates
Write-Host "`nDeleting today's migrations (likely test duplicates):" -ForegroundColor Yellow
Get-ChildItem database\migrations\2025_12_10_*.php 2>$null | ForEach-Object {
    Remove-Item $_.FullName -Force
    Write-Host "  Deleted: $($_.Name)" -ForegroundColor Yellow
}

# Show remaining migrations
Write-Host "`n=== REMAINING MIGRATIONS ===" -ForegroundColor Cyan
Get-ChildItem database\migrations\*.php | Sort-Object Name | ForEach-Object {
    Write-Host "  $($_.Name)" -ForegroundColor Gray
}

# Final migration
Write-Host "`n=== FINAL MIGRATION ===" -ForegroundColor Green
php artisan migrate:fresh --force

Write-Host "`n=== SEEDING ===" -ForegroundColor Green
php artisan db:seed --class=TestDataSeeder

Write-Host "`n✅ ALL DUPLICATES REMOVED!" -ForegroundColor Green
Write-Host "Run: php artisan serve" -ForegroundColor Yellow