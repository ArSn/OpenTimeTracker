FROM php:7.0-apache

COPY . /var/www/html/

COPY opentimetracker.conf /etc/apache2/sites-enabled/

RUN chmod 755 -R /var/www/html &&\
	chmod -R o+w /var/www/html/storage

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
        libmcrypt-dev \
    && docker-php-ext-install -j$(nproc) mcrypt

EXPOSE 80