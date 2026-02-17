FROM php:8.2-fpm

# Install dependencies (minimal set for production)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl zip unzip nginx supervisor \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libmagickwand-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd ftp exif \
    && pecl install redis imagick \
    && docker-php-ext-enable redis imagick \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy only dependency files first → better layer caching
COPY composer.* ./
RUN composer install --optimize-autoloader --prefer-dist --no-scripts --no-interaction

# Copy full application
COPY . .

# Optimize autoload + Laravel production caches
RUN composer dump-autoload --optimize --classmap-authoritative \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    # Optional: if you use events or other cached things
    # && php artisan event:cache

# Permissions (very important in production)
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage /app/bootstrap/cache

# Custom PHP settings
RUN { \
    echo "memory_limit=512M"; \
    echo "upload_max_filesize=100M"; \
    echo "post_max_size=100M"; \
    echo "max_execution_time=120"; \
    echo "opcache.enable=1"; \
    echo "opcache.memory_consumption=256"; \
    echo "opcache.interned_strings_buffer=16"; \
    echo "opcache.max_accelerated_files=30000"; \
    echo "opcache.validate_timestamps=0"; \
    echo "opcache.save_comments=1"; \
    echo "opcache.enable_file_override=1"; \
    echo "opcache.fast_shutdown=1"; \
} > /usr/local/etc/php/conf.d/custom.ini

# PHP-FPM pool override (better defaults for production)
RUN { \
    echo "[www]"; \
    echo "pm = dynamic"; \
    echo "pm.max_children = 30"; \
    echo "pm.start_servers = 6"; \
    echo "pm.min_spare_servers = 3"; \
    echo "pm.max_spare_servers = 12"; \
    echo "pm.max_requests = 500"; \
    echo "request_terminate_timeout = 90s"; \
    echo "listen = /run/php-fpm.sock"; \
    echo "listen.owner = www-data"; \
    echo "listen.group = www-data"; \
    echo "listen.mode = 0660"; \
} > /usr/local/etc/php-fpm.d/zz-performance.conf

# Optimized Nginx config (Unix socket + production headers)
RUN { \
    echo "server {"; \
    echo "    listen 8000;"; \
    echo "    server_name _;"; \
    echo "    root /app/public;"; \
    echo "    index index.php;"; \
    echo ""; \
    echo "    add_header X-Content-Type-Options nosniff;"; \
    echo "    add_header X-Frame-Options SAMEORIGIN;"; \
    echo "    add_header X-XSS-Protection \"1; mode=block\";"; \
    echo ""; \
    echo "    location / {"; \
    echo "        try_files \$uri \$uri/ /index.php?\$query_string;"; \
    echo "    }"; \
    echo ""; \
    echo "    location ~ \.php$ {"; \
    echo "        fastcgi_pass unix:/run/php-fpm.sock;"; \
    echo "        fastcgi_index index.php;"; \
    echo "        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;"; \
    echo "        include fastcgi_params;"; \
    echo "        fastcgi_buffer_size 128k;"; \
    echo "        fastcgi_buffers 4 256k;"; \
    echo "        fastcgi_busy_buffers_size 256k;"; \
    echo "    }"; \
    echo ""; \
    echo "    location ~ /\.ht {"; \
    echo "        deny all;"; \
    echo "    }"; \
    echo "}"; \
} > /etc/nginx/sites-available/default

# Supervisor config stays mostly the same, but ensure correct paths
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create socket directory with correct permissions
RUN mkdir -p /run && chown www-data:www-data /run

EXPOSE 8000

# Production entrypoint: ensure .env exists + warm caches (already done) + start supervisor
CMD ["/bin/sh", "-c", "if [ -f .env.$env_postfix ]; then mv .env.$env_postfix .env; else echo 'No custom env file'; fi && \
    php artisan optimize:clear && \
    php artisan optimize && \
    supervisord -n"]