#!/bin/sh -x
rm -rf /var/www/html/beta.old
mv /var/www/html/beta /var/www/html/beta.old
cd ..
cp -r materialscommons /var/www/html/beta
chcon -R -h -t httpd_sys_script_rw_t /var/www/html/beta
cd /var/www/html/beta
/usr/local/bin/composer install --optimize-autoloader --no-dev
php artisan config:cache
#php artisan migrate
cd ..
chown -R nginx:nginx beta
#systemctl stop supervisord
#sleep 10
#systemctl start supervisord
