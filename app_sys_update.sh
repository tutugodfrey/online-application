#!/bin/bash
cd /var/www/vhosts/online-application
app/Console/cake Migrations.migration run all -c migration -i default
