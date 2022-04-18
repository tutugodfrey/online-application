#!/bin/bash


#Database parameters
DB_FILE=/var/www/vhosts/online-application/app/Config/database.php
CORE_FILE=/var/www/vhosts/online-application/app/Config/core.php
EMAIL_FILE=/var/www/vhosts/online-application/app/Config/email.php
SES_CRED_FILE=/etc/postfix/sasl_passwd
PHP_UNIT_FILE=/var/www/vhosts/online-application/app/Vendor/phpunit/phpunit/PHPUnit/Framework/Comparator/DOMDocument.php

mv /var/www/vhosts/online-application/app/Config/database.php.default /var/www/vhosts/online-application/app/Config/database.php
mv /var/www/vhosts/online-application/app/Config/core.php.default /var/www/vhosts/online-application/app/Config/core.php
mv /var/www/vhosts/online-application/app/Config/email.php.default /var/www/vhosts/online-application/app/Config/email.php

#Update php7.4 incompatible code in PHPUNIT
sed -i 's/$ignoreCase = false/$ignoreCase = false, array \&$processed = array()/' $PHP_UNIT_FILE

#Update DB file based on environemnt
sed -i 's/DATABASE_HOST/'${DATABASE_HOST}'/' $DB_FILE
sed -i 's/DATABASE_NAME/'${DATABASE_NAME}'/' $DB_FILE
sed -i 's/DATABASE_LOGIN/'${DATABASE_LOGIN}'/' $DB_FILE
sed -i 's/DATABASE_PW/'${DATABASE_PW}'/' $DB_FILE

#core.php secrets config
sed -i 's/CORE_SALT/'${CORE_SALT}'/' $CORE_FILE
sed -i 's/CORE_CIPHER_SEED/'${CORE_CIPHER_SEED}'/' $CORE_FILE
sed -i 's/CORE_DB_API_ACCESS_TOKEN/'${CORE_DB_API_ACCESS_TOKEN}'/' $CORE_FILE
sed -i 's/CORE_DB_API_PW/'${CORE_DB_API_PW}'/' $CORE_FILE
sed -i 's/CORE_SSL_CIPHER/'${CORE_SSL_CIPHER}'/' $CORE_FILE
sed -i 's/CORE_SSL_KEY/'${CORE_SSL_KEY}'/' $CORE_FILE
sed -i 's/CORE_SSL_IV/'${CORE_SSL_IV}'/' $CORE_FILE

#email.php secrets config
sed -i 's/EMAIL_HOST/'${EMAIL_HOST}'/' $EMAIL_FILE
sed -i 's/EMAIL_USERNAME/'${EMAIL_USERNAME}'/' $EMAIL_FILE
sed -i 's/EMAIL_PW/'${EMAIL_PW}'/' $EMAIL_FILE

echo "[email-smtp.us-west-2.amazonaws.com]:587 "${SES_USERNAME}":"${SES_PASSWORD} > $SES_CRED_FILE
postmap hash:/etc/postfix/sasl_passwd
exec "$@"
