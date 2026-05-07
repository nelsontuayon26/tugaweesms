@echo off
echo Restoring local .env from backup...
copy /Y .env.local-backup-migration .env
php artisan config:clear
echo Done! Local environment restored.
pause
