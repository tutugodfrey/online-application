!#/bin/bash
php composer.phar install
php composer.phar update
app/Console/cake Migrations.migration run all
