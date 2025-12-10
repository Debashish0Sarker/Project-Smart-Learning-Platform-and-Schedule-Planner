@echo off
echo Feature 3 File Merger
echo ====================
echo.
echo This script will merge your Feature 3 files into the group repository.
echo.
echo Options:
echo   1. Dry Run (see what will change)
echo   2. Execute Merge
echo   3. Exit
echo.

set /p choice="Enter your choice (1-3): "

if "%choice%"=="1" (
    echo.
    echo Starting DRY RUN...
    echo.
    powershell -ExecutionPolicy Bypass -File "%~dp0merge-feature3.ps1" -DryRun
    pause
) else if "%choice%"=="2" (
    echo.
    echo Starting MERGE...
    echo WARNING: This will modify files in the current directory!
    echo.
    set /p confirm="Are you sure? (yes/no): "
    if /i "%confirm%"=="yes" (
        powershell -ExecutionPolicy Bypass -File "%~dp0merge-feature3.ps1"
    ) else (
        echo Merge cancelled.
    )
    pause
) else (
    echo Exiting...
    timeout /t 2 >nul
)