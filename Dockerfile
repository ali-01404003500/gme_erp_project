FROM dunglas/frankenphp

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libzip-dev libpng-dev \
    libmagickwand-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql zip gd ftp pcntl \
    && pecl install redis imagick \
    && docker-php-ext-enable redis imagick pcntl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app

COPY composer.* ./
RUN composer install --optimize-autoloader --no-scripts

COPY . .

RUN composer dump-autoload --optimize
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage

# Custom PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    { \
        echo "max_input_vars = 5000000"; \
        echo "max_multipart_body_parts = 5000000"; \
        echo "upload_max_filesize = 1000M"; \
        echo "post_max_size = 1000M"; \
        echo "memory_limit = 2048M"; \
        echo "max_execution_time = 3000"; \
        echo "max_input_time = 3000"; \
        echo "opcache.enable=1"; \
        echo "opcache.memory_consumption=256"; \
        echo "opcache.max_accelerated_files=20000"; \
    } > "$PHP_INI_DIR/conf.d/docker-php-config.ini"

# Create log directory for supervisor
RUN mkdir -p /var/log/supervisor

# Copy configs
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8000    
CMD ["/bin/sh", "-c", "if [ -f .env.$env_postfix ]; then mv .env.$env_postfix .env; else echo 'No env postfix file found'; fi && supervisord -n"]
