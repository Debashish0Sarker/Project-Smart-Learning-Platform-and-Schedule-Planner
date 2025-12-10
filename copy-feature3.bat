@echo off
echo ========================================
echo FEATURE 3 FILE COPY TOOL
echo ========================================
echo.
echo This tool will copy Feature 3 files to the group repository.
echo.
echo WARNING: This will OVERWRITE existing files with the same name!
echo.

:menu
echo Options:
echo   1. Show what will be copied (Dry Run)
echo   2. Copy files now
echo   3. Exit
echo.
set /p choice="Enter choice (1-3): "

if "%choice%"=="1" goto dryrun
if "%choice%"=="2" goto copy
if "%choice%"=="3" goto exit
goto menu

:dryrun
echo.
echo DRY RUN - No files will be modified
echo.
powershell -ExecutionPolicy Bypass -File "%~dp0merge-feature3-fixed.ps1" -DryRun
echo.
pause
goto menu

:copy
echo.
echo WARNING: This will copy files from:
echo   C:\xampp\htdocs\smart_learning_app\smart-learning-platform
echo TO current directory: %CD%
echo.
set /p confirm="Are you sure? (type YES to continue): "
if /i not "%confirm%"=="YES" (
    echo Cancelled.
    pause
    goto menu
)

echo.
echo Starting file copy...
powershell -ExecutionPolicy Bypass -File "%~dp0merge-feature3-fixed.ps1"
echo.
pause
goto menu

:exit
echo Exiting...
timeout /t 1 >nul