FROM ubuntu:20.04

WORKDIR /tmp

#This dockerfile installs PHP 7.4 + dependencies and apache2 

# disable interactive functions
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update \
    && apt-get install -y wget gnupg2
	
# Install common / shared packages
RUN apt-get install -y \
    curl \
    git \
    zip \
    unzip \
    locales \
    software-properties-common \
    vim

# Set up locales
RUN locale-gen en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

# Install PostgreSQL v11
#RUN wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add -
# RUN apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 --recv-keys B97B0AFCAA1A47F044F244A07FCC7D46ACCC4CF8
# RUN echo "deb http://apt.postgresql.org/pub/repos/apt/ $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list

# RUN add-apt-repository ppa:ondrej/php
# RUN wget http://archive.ubuntu.com/ubuntu/pool/main/o/openssl1.0/libssl1.0.0_1.0.2n-1ubuntu5.6_amd64.deb

#download and install SSH2 for php
RUN apt-get update 
RUN apt-get install curl php-dev php-pear libssh2-1-dev -y
RUN curl http://pecl.php.net/get/ssh2-1.2.tgz -o ssh2.tgz
RUN printf "\n" | pecl install ssh2 ssh2.tgz
RUN rm -rf ssh2.tgz

RUN wget http://archive.ubuntu.com/ubuntu/pool/main/o/openssl1.0/libssl1.0.0_1.0.2n-1ubuntu5.8_amd64.deb
RUN dpkg -i libssl1.0.0_1.0.2n-1ubuntu5.8_amd64.deb
RUN apt-get update \
&& apt-get install -y --no-install-recommends \
gcc make libpq5 \
apache2 \
libpq-dev \
php7.4 libapache2-mod-php7.4 php7.4-bcmath php7.4-zip \
php7.4-common php7.4-mbstring php7.4-xml php7.4-imap \
php7.4-fpm php7.4-cli php7.4-pgsql php7.4-sqlite3 php7.4-redis \
php7.4-apcu php7.4-intl php7.4-json php7.4-gd \
php7.4-memcached php-ssh2 \
openssh-server php7.4-curl && \
phpenmod mcrypt && \
rm -rf /var/lib/apt/lists/* && \
cd /tmp && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Packages remove
# php7.4-mcrypt

# libpq-dev postgresql-client-11 \

	
#Add ssh.ini file and enable ssh2 module
RUN bash -c "echo extension=ssh2.so > /etc/php/7.4/mods-available/ssh2.ini"
RUN phpenmod ssh2

RUN rm -rf /var/www/html && \
	mkdir -p /var/www/vhosts/online-application && \
    rm -rf /etc/apache2/sites-enabled/* && \
	rm /etc/apache2/apache2.conf

# COPY ./10-onlineapp.conf /etc/apache2/sites-available/10-onlineapp.conf
# COPY ./apache2.conf /etc/apache2/apache2.conf
# COPY ./app /var/www/vhosts/online-application/app
# COPY ./startup.sh startup.sh

##VARIABLE DEFNENTIIONS FOR SECRETS IN CONFIG FILES###
ENV DATABASE_HOST=
ENV DATABASE_NAME=
ENV DATABASE_LOGIN=
ENV DATABASE_PW=
ENV CORE_SALT=
ENV CORE_CIPHER_SEED=
ENV CORE_DB_API_ACCESS_TOKEN=
ENV CORE_DB_API_PW=
ENV CORE_SSL_CIPHER=
ENV CORE_SSL_KEY=
ENV CORE_SSL_IV=
ENV EMAIL_HOST=
ENV EMAIL_USERNAME=
ENV EMAIL_PW=
######END####


#Install PHPUnit

RUN curl https://phar.phpunit.de/phpunit-3.7.28.phar > phpunit.phar && \
    chmod +x phpunit.phar && \
    mv /tmp/phpunit.phar /usr/local/bin/phpunit
	
RUN a2ensite 10-onlineapp.conf

# Set php7.4 as default version to use
RUN a2enmod php7.4
RUN a2enmod headers
RUN update-alternatives --set php /usr/bin/php7.4

EXPOSE 80

RUN chmod +x /tmp/startup.sh
ENTRYPOINT [ "/tmp/startup.sh" ]

# Start apache2
CMD ["apachectl", "-D", "FOREGROUND"]
