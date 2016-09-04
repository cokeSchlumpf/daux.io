FROM php:7.0-apache
MAINTAINER "Michael Wellner" <michael.wellner@de.ibm.com>

EXPOSE 80

# Download the daux.io archive on github
# ADD http://github.com/justinwalsh/daux.io/archive/master.tar.gz /var/www/html/
COPY master.tar.gz /var/www/html/

# Untar the archive
WORKDIR /var/www/html
RUN \
  tar xvf master.tar.gz -C /var/www/html && \
  rm master.tar.gz && \
  cp -r daux.io-master/* daux.io-master/.htaccess /var/www/html/ && \
  rm -rf /var/www/html/daux.io-master && \
  rm -r /var/www/html/docs/* && \
  chgrp -R www-data /var/www/html && \
  chown -R www-data /var/www/html

# Setup apache
RUN \
  a2enmod rewrite && \
  rm -rf /etc/apache2/sites-enabled/*

COPY daux.io.conf /etc/apache2/sites-enabled/daux.io.conf
COPY update-webhook.php /var/www/html/
