FROM php:7.4-apache

RUN apt-get update

#COPY ./public /var/www/html

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN a2enmod rewrite
RUN a2enmod headers

RUN service apache2 restart
