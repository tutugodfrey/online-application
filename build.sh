#!/bin/bash
mkdir -p app/tmp
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:${HTTPDUSER}:rwx app/tmp
sudo setfacl -R -d -m u:${HTTPDUSER}:rwx app/tmp
CURRENTUSER=`whoami`
sudo setfacl -R -m u:${CURRENTUSER}:rwx app/tmp
sudo composer self-update
composer update
app/Console/cake Migrations.migration run all
