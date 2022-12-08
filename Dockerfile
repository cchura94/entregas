#!/usr/bin/env bash

composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

php artisan config:cache

php artisan route:cache

npm install

npm run dev

php artisan migrate --seed