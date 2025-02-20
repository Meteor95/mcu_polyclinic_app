ARG ALPINE_VERSION=3.20
FROM alpine:${ALPINE_VERSION}
LABEL Maintainer="Mochmad Aries Setyawan <seira@erayadigital.co.id>"
LABEL Description="Container for Laravel Octane Artha Medica MCU"

# Setup document root
WORKDIR /var/www/html

# Install packages and PHP extensions
RUN apk add --no-cache \
  curl \
  php83 \
  php83-ctype \
  php83-curl \
  php83-dom \
  php83-fileinfo \
  php83-fpm \
  php83-gd \
  php83-intl \
  php83-mbstring \
  php83-mysqli \
  php83-opcache \
  php83-openssl \
  php83-phar \
  php83-session \
  php83-tokenizer \
  php83-xml \
  php83-xmlreader \
  php83-xmlwriter \
  php83-simplexml \
  php83-pdo_mysql \
  php83-sqlite3 \
  php83-pdo_sqlite \
  php83-pdo \
  php83-pear \
  php83-redis \
  php83-iconv \
  imagemagick \
  imagemagick-dev \
  supervisor \
  autoconf \
  automake \
  make \
  gcc \
  g++ \
  libtool \
  pkgconfig \
  php83-dev \
  nginx

# Install Imagick
RUN pecl install imagick && docker-php-ext-enable imagick
# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install OpenSwoole
RUN pecl install openswoole

# Install additional PHP extensions
RUN apk add --no-cache php83-pcntl php83-posix php83-bcmath php83-sockets

# Configure PHP-FPM
ENV PHP_INI_DIR=/etc/php83
COPY config/php.ini ${PHP_INI_DIR}/conf.d/custom.ini

# Configure supervisord
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Ensure files/folders are accessible to the 'nobody' user
RUN chown -R nobody.nobody /var/www/html /run

# Add composer.json and other application files
COPY --chown=nobody source/composer.json /var/www/html/composer.json
COPY --chown=nobody source/artisan /var/www/html/artisan
COPY --chown=nobody source/bootstrap/ /var/www/html/bootstrap/
COPY --chown=nobody source/routes/ /var/www/html/routes/
COPY --chown=nobody source/config/ /var/www/html/config/
COPY --chown=nobody source/ /var/www/html/

# Run composer install
RUN composer install --optimize-autoloader --no-interaction --verbose

# Switch to use a non-root user from here on
USER nobody

# Expose port for nginx
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Healthcheck
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/up || exit 1
