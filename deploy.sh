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

npm run prod
sudo ./deploy2.sh

NGINXUSERACC="${NGINXUSER:-nginx}"
sudo chown -R $NGINXUSERACC:$NGINXUSERACC /var/www/html/materialscommons

SUPERVISORSERVICENAME="${SUPERVISORSERVICE:-supervisord}"
sudo systemctl status ${SUPERVISORSERVICENAME}
php artisan queue:restart
sleep 2
sudo systemctl status ${SUPERVISORSERVICENAME}
