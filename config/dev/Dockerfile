ARG ALPINE_VERSION=3.20
FROM alpine:${ALPINE_VERSION}
LABEL Maintainer="Mochmad Aries Setyawan <seira@erayadigital.co.id>"
LABEL Description="Container for Laravel Octane Artha Medical Centre MCU"

WORKDIR /var/www/html

# Install PHP and extensions (grouped for efficiency)
RUN apk add --no-cache \
    php83 \
    php83-ctype \
    php83-curl \
    php83-pcntl \
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
    php83-dev \
    php83-cli \
    php83-zip \
    php83-posix \   
    php83-pecl-imagick \
    imagemagick \
    ghostscript \
    curl

# Install and configure OpenSwoole
RUN apk add --no-cache \
    gcc \
    g++ \
    make \
    autoconf \
    pkgconfig \
    git && \
    pecl install openswoole && \
    # Clean up build dependencies
    apk del gcc g++ make autoconf pkgconfig git

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configure PHP-FPM
ENV PHP_INI_DIR=/etc/php83
COPY config/php.ini ${PHP_INI_DIR}/conf.d/custom.ini

# Configure supervisord
RUN apk add --no-cache supervisor
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set up directory permissions
RUN mkdir -p /var/www/html /run && \
    chown -R nobody:nobody /var/www/html /run && \
    # Create Composer cache directory with correct permissions
    mkdir -p /.composer/cache && \
    chown -R nobody:nobody /.composer

# Copy application files
COPY --chown=nobody:nobody source/ /var/www/html/

# Switch to non-root user
USER nobody

# Install dependencies
RUN composer install --optimize-autoloader --no-interaction --no-progress

# Expose port
EXPOSE 8080

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Healthcheck
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/up || exit 1