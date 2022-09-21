#! /bin/bash
ssh $1@$2 "cd $3 && git pull && composer i --no-dev --optimize-autoloader && php artisan migrate && php artisan optimize && php artisan route:cache && php artisan cache:clear"
