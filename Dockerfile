#!/usr/bin/env bash

php artisan config:cache

php artisan route:cache

npm install

npm run dev

php artisan migrate --seed