#!/bin/sh -x
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
systemctl stop supervisord
sleep 10
systemctl start supervisord
