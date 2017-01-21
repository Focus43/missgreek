FROM alpine:3.5

ENV PHPREDIS_VERSION 3.1.0

RUN apk update && \
  apk add --no-cache \
  apache2 \
  apache2-ssl \
  php5 \
  php5-dev \
  php5-apache2 \
  php5-cli \
  php5-phar \
  php5-zlib \
  php5-zip \
  php5-bz2 \
  php5-ctype \
  php5-mysqli \
  php5-mysql \
  php5-pdo_mysql \
  php5-pdo \
  php5-json \
  php5-curl \
  php5-gd \
  php5-mcrypt \
  php5-openssl \
  php5-dom \
  php5-xml \
  php5-iconv \
  curl \
  wget \
  make \
  autoconf \
  gcc \
  g++ \
  supervisor && \
  mkdir -p /run/apache2 && \
  chown -R apache:apache /run/apache2 && \
  mkdir -p /var/www && \
  echo "<?php phpinfo(); ?>" > /var/www/index.php && \
  chown -R apache:apache /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
  php -r "if (hash_file('SHA384', 'composer-setup.php') === '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
  php composer-setup.php && \
  php -r "unlink('composer-setup.php');" && \
  mv composer.phar /usr/local/bin/composer

# Compile and install phpredis
ADD https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz /tmp/redis.tar.gz
RUN tar xzf /tmp/redis.tar.gz -C /tmp && \
  rm /tmp/redis.tar.gz && \
  mv /tmp/phpredis-$PHPREDIS_VERSION /usr/lib/php5/modules/phpredis && \
  cd /usr/lib/php5/modules/phpredis && phpize && ./configure && make && make install && \
  cd / && rm -rf /usr/lib/php5/modules/phpredis

# https://hub.docker.com/r/janes/alpine-lamp/~/dockerfile/
COPY ./_docker/config/httpd.conf /etc/apache2/httpd.conf
COPY ./_docker/config/vhost.conf /etc/apache2/vhost.conf

# copy the self-signed SSL certificate
COPY ./_docker/config/docker_apache_ssl.pem /etc/ssl/certs/docker_apache_ssl.pem

# https://hub.docker.com/r/janes/alpine-lamp/~/dockerfile/
COPY ./_docker/config/php.ini /etc/php5/php.ini

# Supervisor configuration
COPY ./_docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
