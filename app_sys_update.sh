#!/bin/bash
cd /var/www/vhosts/axia-db
app/Console/cake Migrations.migration run all -c migration -i default