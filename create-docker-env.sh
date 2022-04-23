#! /bin/bash

echo "DATABASE_HOST=${DATABASE_HOST}" >> docker-env
echo "DATABASE_LOGIN=${DATABASE_LOGIN}" >> docker-env
echo "DATABASE_NAME=${DATABASE_NAME}" >> docker-env
echo "DATABASE_PW=${DATABASE_PW}" >> docker-env
echo "CORE_SALT=${CORE_SALT}" >> docker-env
echo "CORE_CIPHER_SEED=${CORE_CIPHER_SEED}" >> docker-env
echo "CORE_DB_API_ACCESS_TOKEN=${CORE_DB_API_ACCESS_TOKEN}" >> docker-env
echo "CORE_DB_API_PW=${CORE_DB_API_PW}" >> docker-env
echo "CORE_SSL_CIPHER=${CORE_SSL_CIPHER}" >> docker-env
echo "CORE_SSL_KEY=${CORE_SSL_KEY}" >> docker-env
echo "CORE_SSL_IV=${CORE_SSL_IV}" >> docker-env
echo "EMAIL_HOST=${EMAIL_HOST}" >> docker-env
echo "EMAIL_PW=${EMAIL_PW}" >> docker-env
echo "EMAIL_USERNAME=${EMAIL_USERNAME}" >> docker-env
