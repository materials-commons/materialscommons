#!/usr/bin/env bash

read -p "Did you run all unit tests? [y/n] " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
  echo "Stopping so you can run unit tests and make sure they all pass."
  exit 0
fi

set -x

SRC_DIR=$(pwd)

DEPLOY_DATE=$(date +'%Y-%m-%dT%H:%M:%S.%Z')
CURRENT_VERSION=$(grep MC_SERVER_VERSION .env | sed 's/MC_SERVER_VERSION=//')

cp .env .env.save
LAST_VERSION_NUMBER=$(echo $CURRENT_VERSION | cut -d. -f3)
NEXT_V=$(($LAST_VERSION_NUMBER + 1))
FIRST_V=$(echo $CURRENT_VERSION | cut -d. -f1)
SECOND_V=$(echo $CURRENT_VERSION | cut -d. -f2)
cat .env | grep -v MC_SERVER_VERSION | grep -v MC_SERVER_LAST_UPDATED_AT >.env.tmp
echo "MC_SERVER_LAST_UPDATED_AT=${DEPLOY_DATE}" >>.env.tmp
echo "MC_SERVER_VERSION=${FIRST_V}.${SECOND_V}.${NEXT_V}" >>.env.tmp
mv .env.tmp .env

rm -rf /var/www/html/materialscommons.old
mv /var/www/html/materialscommons /var/www/html/materialscommons.old
cd ..
cp -r materialscommons /var/www/html/materialscommons
chcon -R -h -t httpd_sys_script_rw_t /var/www/html/materialscommons
cd /var/www/html/materialscommons
/usr/local/bin/composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan migrate
cd ..
chown -R nginx:nginx materialscommons
cd "${SRC_DIR}"
chown gtarcea:umuser .env .env.save
php artisan queue:restart
systemctl status supervisord
