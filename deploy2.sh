#!/usr/bin/env bash

DEPLOYTO=${1:-/var/www/html/materialscommons}
set -x
rm -rf ${DEPLOYTO}.old
mv ${DEPLOYTO} ${DEPLOYTO}.old
cd ..
cp -r materialscommons ${DEPLOYTO}
# chcon -R -h -t httpd_sys_script_rw_t ${DEPLOYTO}
cd ${DEPLOYTO}
rm -rf .git
rm -rf .idea
/usr/local/bin/composer install --optimize-autoloader --no-dev --no-interaction
php artisan config:cache
php artisan migrate --force

#chown gtarcea:umuser .env .env.save
