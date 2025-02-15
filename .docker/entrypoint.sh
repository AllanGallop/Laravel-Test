#!/bin/bash

# Run Composer install
composer install --no-interaction --prefer-dist --optimize-autoloader

# Run database migrations and seeders
php artisan migrate:fresh --force
php artisan db:seed --force

# Start Apache
service apache2 start

# Build assets
npm install && npm run build

# Start Cron
cron

#Start Supervisor
/usr/bin/supervisord -n