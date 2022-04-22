#!/bin/bash

cd /var/www/vhosts/online-application
mkdir -p app/tmp
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
setfacl -R -m u:${HTTPDUSER}:rwx app/tmp
setfacl -R -d -m u:${HTTPDUSER}:rwx app/tmp
CURRENTUSER=`whoami`
setfacl -R -m u:${CURRENTUSER}:rwx app/tmp
composer self-update
composer update
app/Console/cake Migrations.migration run all
