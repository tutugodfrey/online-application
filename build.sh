#!/bin/bash

cd /var/www/vhosts/online-application
mkdir -p app/tmp

mkdir /var/www/vhosts/online-application/app/tmp/logs
chmod 777 /var/www/vhosts/online-application/app/tmp/logs
mkdir /var/www/vhosts/online-application/app/tmp/cache
chmod 777 /var/www/vhosts/online-application/app/tmp/cache
mkdir /var/www/vhosts/online-application/app/tmp/cache/persistent
chmod 777 /var/www/vhosts/online-application/app/tmp/cache/persistent
mkdir /var/www/vhosts/online-application/app/tmp/cache/models
chmod 777 /var/www/vhosts/online-application/app/tmp/cache/models

HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
setfacl -R -m u:${HTTPDUSER}:rwx /var/www/vhosts/online-application
setfacl -R -d -m u:${HTTPDUSER}:rwx /var/www/vhosts/online-application
CURRENTUSER=`whoami`
echo " "
echo $CURRENTUSER
echo " " 
setfacl -R -m u:${CURRENTUSER}:rwx /var/www/vhosts/online-application
composer self-update
composer update
app/Console/cake Migrations.migration run all
