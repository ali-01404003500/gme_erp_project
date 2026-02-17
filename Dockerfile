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

RUN { \
    echo "memory_limit=512M"; \
    echo "upload_max_filesize=100M"; \
    echo "post_max_size=100M"; \
    echo "max_execution_time=300"; \
} > /usr/local/etc/php/conf.d/custom.ini



RUN { \
    echo "server {"; \
    echo "    listen 8000;"; \
    echo "    root /app/public;"; \
    echo "    index index.php;"; \
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
