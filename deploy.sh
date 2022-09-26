#!/bin/bash
tmp=/dev/shm/cost-monitoring
rm -rf ${tmp} && \
git clone -l -s . ${tmp} -b master && \
cd ${tmp} && \
echo "Building..." && \
composer i --no-dev --optimize-autoloader && \
rm -rf .env.example .gitattributes composer.lock .git* README.md && \
echo "Deploying..." && \
rsync -av --delete --exclude ".env" --exclude "/storage" ./ user@143.198.113.70:backend/ && \
ssh user@143.198.113.70 "cd backend && php artisan migrate && php artisan optimize && php artisan route:cache && php artisan cache:clear" && \
echo "Done in ${SECONDS} sec."
