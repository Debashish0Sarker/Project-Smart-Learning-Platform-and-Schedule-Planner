# remove-all-duplicates.ps1
cd C:\xampp\htdocs\smart_learning_app\Project-Smart-Learning-Platform-and-Schedule-Planner

Write-Host "=== SMART DUPLICATE REMOVER ===" -ForegroundColor Magenta
Write-Host "This will keep the EARLIEST version of each migration" -ForegroundColor Yellow

# 1. Backup first!
$backupDir = "database\migrations-backup-$(Get-Date -Format 'yyyyMMdd_HHmmss')"
Copy-Item database\migrations $backupDir -Recurse
Write-Host "`n1. Backup created: $backupDir" -ForegroundColor Green

# 2. Group migrations by type (ignoring timestamp)
$migrations = Get-ChildItem database\migrations\*.php | Sort-Object Name
$migrationGroups = $migrations | Group-Object { 
    $_.Name -replace '^\d{4}_\d{2}_\d{2}_\d{6}_', '' -replace '\.php$', ''
}

Write-Host "`n2. Found $($migrationGroups.Count) unique migration types" -ForegroundColor Cyan

# 3. Process each group
$totalDeleted = 0
$totalKept = 0

Write-Host "`n3. Processing duplicates..." -ForegroundColor Cyan
foreach ($group in $migrationGroups | Sort-Object Name) {
    Write-Host "`n[$($group.Name)]" -ForegroundColor White
    
    if ($group.Count -eq 1) {
        # No duplicates, keep it
        Write-Host "  ✓ Unique - keeping: $($group.Group[0].Name)" -ForegroundColor Green
        $totalKept++
    }
    else {
        # Has duplicates - keep earliest, delete others
        $sorted = $group.Group | Sort-Object Name
        $keep = $sorted[0]  # Earliest timestamp
        
        Write-Host "  ✓ Keeping (earliest): $($keep.Name)" -ForegroundColor Green
        $totalKept++
        
        # Delete the rest
        $toDelete = $sorted[1..$sorted.Count]
        foreach ($migration in $toDelete) {
            Remove-Item $migration.FullName -Force
            Write-Host "  ✗ Deleting (duplicate): $($migration.Name)" -ForegroundColor Yellow
            $totalDeleted++
        }
    }
}

# 4. Show summary
Write-Host "`n" + "="*50 -ForegroundColor Cyan
Write-Host "SUMMARY" -ForegroundColor Magenta
Write-Host "="*50 -ForegroundColor Cyan
Write-Host "Total migrations processed: $($migrations.Count)" -ForegroundColor White
Write-Host "Unique migrations kept: $totalKept" -ForegroundColor Green
Write-Host "Duplicate migrations deleted: $totalDeleted" -ForegroundColor Yellow

# 5. Show final migration list
Write-Host "`n4. Final migration list:" -ForegroundColor Cyan
Get-ChildItem database\migrations\*.php | Sort-Object Name | ForEach-Object {
    Write-Host "  $($_.Name)" -ForegroundColor Gray
}

# 6. Run migrations
Write-Host "`n5. Running fresh migrations..." -ForegroundColor Green
php artisan migrate:fresh --force

Write-Host "`n6. Seeding database..." -ForegroundColor Green
php artisan db:seed --class=TestDataSeeder

Write-Host "`n" + "="*50 -ForegroundColor Green
Write-Host "✅ ALL DUPLICATES REMOVED SUCCESSFULLY!" -ForegroundColor Green
Write-Host "="*50 -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "1. Test your app: php artisan serve" -ForegroundColor White
Write-Host "2. Backup location: $backupDir" -ForegroundColor Cyan
Write-Host "3. If anything breaks, restore from backup" -ForegroundColor Cyan