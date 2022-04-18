FROM ubuntu:20.04

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
RUN apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 --recv-keys B97B0AFCAA1A47F044F244A07FCC7D46ACCC4CF8
RUN echo "deb http://apt.postgresql.org/pub/repos/apt/ $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list

RUN add-apt-repository ppa:ondrej/php
RUN wget http://archive.ubuntu.com/ubuntu/pool/main/o/openssl1.0/libssl1.0.0_1.0.2n-1ubuntu5.6_amd64.deb
RUN dpkg -i libssl1.0.0_1.0.2n-1ubuntu5.6_amd64.deb
RUN apt-get update \
&& apt-get install -y --no-install-recommends \
gcc make libpq5 \
apache2 \
libpq-dev postgresql-client-11 \
php7.4 php7.4-dev php-pear libapache2-mod-php7.4 php7.4-bcmath php7.4-zip \
php7.4-common php7.4-mbstring php7.4-xml php7.4-imap \
php7.4-fpm php7.4-cli php7.4-pgsql php7.4-sqlite3 php7.4-redis \
php7.4-apcu php7.4-intl php7.4-mcrypt php7.4-json php7.4-gd \
php7.4-memcached libssh2-1-dev libssh2-1 php-ssh2 \
openssh-server php7.4-curl && \
phpenmod mcrypt && \
rm -rf /var/lib/apt/lists/* && \
cd /tmp && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

#download and install SSH2 for php
RUN curl http://pecl.php.net/get/ssh2-1.2.tgz -o ssh2.tgz && \
    pecl install ssh2 ssh2.tgz && \
    rm -rf ssh2.tgz
	
#Add ssh.ini file and enable ssh2 module
RUN bash -c "echo extension=ssh2.so > /etc/php/7.4/mods-available/ssh2.ini"
RUN phpenmod ssh2

RUN rm -rf /var/www/html && \
	mkdir -p /var/www/vhosts/online-application && \
    rm -rf /etc/apache2/sites-enabled/* && \
	rm /etc/apache2/apache2.conf

COPY ./configFiles/10-onlineapp.conf /etc/apache2/sites-available/10-onlineapp.conf
COPY ./configFiles/apache2.conf /etc/apache2/apache2.conf
COPY ./startup.sh /tmp/startup.sh

##SAMPLE VARIABLE DEFNENTIION###
ENV PROD_HOST=
ENV PROD_DBNAME=
######END####

RUN chmod +x /tmp/deploy.sh
ENTRYPOINT [ "/tmp/deploy.sh" ]

#Install PHPUnit

WORKDIR /tmp

RUN curl https://phar.phpunit.de/phpunit-3.7.28.phar > phpunit.phar && \
    chmod +x phpunit.phar && \
    mv /tmp/phpunit.phar /usr/local/bin/phpunit
	
RUN a2ensite 10-onlineapp.conf

# Set php7.4 as default version to use
RUN a2enmod php7.4
RUN a2enmod headers
RUN update-alternatives --set php /usr/bin/php7.4

#start apache2
CMD ["apachectl", "-D", "FOREGROUND"]

EXPOSE 80
