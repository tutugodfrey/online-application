!#/bin/bash
cd app
Console/cake Migrations.migration run all
php ../codecept.phar run CobrandedOnlineapp --steps
