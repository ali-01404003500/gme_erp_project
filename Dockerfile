FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip nginx \        
    libzip-dev libpng-dev \
    libmagickwand-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql zip gd ftp \
    && pecl install redis imagick \
    && docker-php-ext-enable redis imagick \
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


RUN { \
    echo "server {"; \
    echo "    listen 8000;"; \
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
    echo "        fastcgi_pass 127.0.0.1:9000;"; \
    echo "        fastcgi_index index.php;"; \
    echo "        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;"; \
    echo "        include fastcgi_params;"; \
    echo "    }"; \
    echo ""; \
    echo "    location ~ /\.ht {"; \
    echo "        deny all;"; \
    echo "    }"; \
    echo "}"; \
} > /etc/nginx/sites-available/default

# Copy configs
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8000    
CMD ["/bin/sh", "-c", "if [ -f .env.$env_postfix ]; then mv .env.$env_postfix .env; else echo 'No env postfix file found'; fi && supervisord -n"]
