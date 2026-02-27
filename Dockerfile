# Production Dockerfile for CodeIgniter 3 / Perfex CRM
# Requires PHP 8.1+
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies, PHP extensions, and Nginx
RUN apt-get update && apt-get install -y \
    git \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    unzip \
    curl \
    libmagickwand-dev \
    libkrb5-dev \
    nginx \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        intl \
        mysqli \
        pdo_mysql \
        zip \
        opcache \
        gd \
        soap \
        bcmath \
        exif \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && rm -rf /var/lib/apt/lists/*

# Try to install IMAP extension - skip if package not available
RUN apt-get update && \
    (apt-get install -y libc-client-dev libkrb5-dev && \
     docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
     docker-php-ext-install imap && \
     rm -rf /var/lib/apt/lists/*) || \
    (echo "IMAP extension not available, will use --ignore-platform-req during composer install" && \
     rm -rf /var/lib/apt/lists/*)

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copy nginx and PHP-FPM configurations
COPY nginx.conf /etc/nginx/nginx.conf
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy composer files first for better layer caching
COPY application/composer.json application/composer.lock* /var/www/html/application/

# Install production dependencies (no dev dependencies)
# Use --ignore-platform-req=ext-imap if IMAP extension is not available
WORKDIR /var/www/html/application
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts --ignore-platform-req=ext-imap || \
    composer install --no-dev --no-interaction --optimize-autoloader --no-scripts \
    && composer dump-autoload --optimize --classmap-authoritative

# Copy application files
WORKDIR /var/www/html
COPY . /var/www/html/

# Install module dependencies if they exist (must be after COPY)
RUN find /var/www/html/modules -name "composer.json" -type f -execdir composer install --no-dev --no-interaction --optimize-autoloader \; || true

# Remove macOS metadata if present
RUN rm -rf __MACOSX || true

# Create nginx cache directories
RUN mkdir -p /var/cache/nginx/fastcgi \
    && mkdir -p /var/log/nginx \
    && chown -R www-data:www-data /var/cache/nginx \
    && chown -R www-data:www-data /var/log/nginx

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/application/cache \
    && chmod -R 775 /var/www/html/application/logs \
    && chmod -R 775 /var/www/html/uploads \
    && chmod -R 775 /var/www/html/temp \
    && chmod -R 775 /var/www/html/backups

# Configure PHP for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.enable_cli=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# PHP production settings
RUN echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_input_time=300" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/uploads.ini

# Security: Hide PHP version
RUN echo "expose_php = Off" >> /usr/local/etc/php/conf.d/security.ini

# Set timezone
RUN echo "date.timezone = UTC" >> /usr/local/etc/php/conf.d/timezone.ini

# Create supervisor configuration for running both nginx and PHP-FPM
RUN echo "[supervisord]" > /etc/supervisor/conf.d/supervisord.conf \
    && echo "nodaemon=true" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "[program:php-fpm]" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "command=php-fpm" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "autostart=true" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "autorestart=true" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "stderr_logfile=/var/log/php-fpm.err.log" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "stdout_logfile=/var/log/php-fpm.out.log" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "[program:nginx]" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "command=nginx -g 'daemon off;'" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "autostart=true" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "autorestart=true" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "stderr_logfile=/var/log/nginx.err.log" >> /etc/supervisor/conf.d/supervisord.conf \
    && echo "stdout_logfile=/var/log/nginx.out.log" >> /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Start supervisor to manage nginx and PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
