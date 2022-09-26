#!/bin/bash
composer i --no-dev --optimize-autoloader && \
rsync -av --delete --exclude ".env" --exclude "/storage" ./ $1@$2:backend/ && \
ssh $1@$2 "cd backend && php artisan migrate && php artisan optimize && php artisan route:cache && php artisan cache:clear" && \
echo 'DONE' && \
composer i
