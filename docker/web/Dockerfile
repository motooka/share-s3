FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get upgrade -y

# see https://askubuntu.com/questions/909277/avoiding-user-interaction-with-tzdata-when-installing-certbot-in-a-docker-contai
RUN apt-get install -y tzdata

# CakePHP dependencies and minimum requirements for apps with PHP / Composer
RUN apt-get install -y apache2 php8.1 php8.1-mbstring php8.1-intl php8.1-xml ca-certificates zip composer php8.1-curl

# PHP extensions for local development
RUN apt-get install -y php8.1-sqlite3

# for debugging
RUN apt-get install -y vim less ca-certificates


# composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY composer_install.sh /
RUN chmod 755 /composer_install.sh

# apache setting
COPY site.conf /etc/apache2/conf-enabled/
RUN cd /etc/apache2/mods-enabled; ln -s ../mods-available/rewrite.load ./

# document root
RUN rm -rf /var/www/html
RUN cd /var/www; ln -s /repository ./html


# the process
CMD ["apachectl", "-D", "FOREGROUND"]
