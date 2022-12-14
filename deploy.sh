#!/bin/bash
tmp=/dev/shm/cost-monitoring
rm -rf ${tmp} && \
git clone -l -s . ${tmp} -b master && \
cd ${tmp} && \
echo "Building..." && \
composer i --no-dev --optimize-autoloader && \
rm -rf .env.example .gitattributes composer.lock .git* README.md && \
echo "Deploying..." && \
rsync -av --delete --exclude ".env" --exclude "/storage" ./ user@142.93.102.201:backend/ && \
ssh user@142.93.102.201 "cd backend && php artisan migrate && php artisan optimize && php artisan route:cache && php artisan cache:clear && pm2 restart all" && \
echo "Done in ${SECONDS} sec."
