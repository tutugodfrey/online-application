#!/bin/bash


#Database parameters
DB_FILE=/var/www/vhosts/online-application/app/Config/database.php
SES_CRED_FILE=/etc/postfix/sasl_passwd

mv /var/www/vhosts/online-application/app/Config/database.php.default /var/www/vhosts/online-application/app/Config/database.php

#Update DB file based on environemnt

sed -i 's/PROD_HOST/'${PROD_DBHOST}'/' $DB_FILE
sed -i 's/PROD_DBNAME/'${PROD_DATABASE}'/' $DB_FILE
sed -i 's/PROD_DBUSER/'${PROD_DATABASEUSER}'/' $DB_FILE
sed -i 's/PROD_PASSWORD/'${DB_PASSWORD}'/' $DB_FILE

sed -i 's/DEV_HOST/'${DEV_DBHOST}'/' $DB_FILE
sed -i 's/DEV_DBNAME/'${DEV_DATABASE}'/' $DB_FILE
sed -i 's/DEV_DBUSER/'${DEV_DATABASEUSER}'/' $DB_FILE
sed -i 's/DEV_PASSWORD/'${DEV_PASSWORD}'/' $DB_FILE

sed -i 's/ALT_HOST/'${ALT_DBHOST}'/' $DB_FILE
sed -i 's/ALT_DBNAME/'${ALT_DATABASE}'/' $DB_FILE
sed -i 's/ALT_DBUSER/'${ALT_DATABASEUSER}'/' $DB_FILE
sed -i 's/ALT_PASSWORD/'${ALT_PASSWORD}'/' $DB_FILE

echo "[email-smtp.us-west-2.amazonaws.com]:587 "${SES_USERNAME}":"${SES_PASSWORD} > $SES_CRED_FILE
postmap hash:/etc/postfix/sasl_passwd
exec "$@"
