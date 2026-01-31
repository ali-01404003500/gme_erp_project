FROM php:8.2

RUN apt-get update -y && apt-get install -y \
    openssl zip unzip build-essential git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libpng-dev \
    cron \
    htop \
    libmagickwand-dev \
    libmagickcore-dev \
    && pecl install redis-5.3.7 \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*


RUN docker-php-ext-install pdo pdo_mysql zip ftp gd \
    && pecl install imagick \
    && docker-php-ext-enable imagick
WORKDIR /app
COPY composer.* /app


COPY crontab /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron
RUN crontab /etc/cron.d/laravel-cron

RUN echo "max_input_vars = 5000000" >> /usr/local/etc/php/php.ini
RUN echo "max_multipart_body_parts = 5000000" >> /usr/local/etc/php/php.ini
RUN echo "upload_max_filesize = 1000M" >> /usr/local/etc/php/php.ini
RUN echo "post_max_size = 1000M" >> /usr/local/etc/php/php.ini
RUN echo "memory_limit = 4000M" >> /usr/local/etc/php/php.ini
RUN echo "max_execution_time = 3000" >> /usr/local/etc/php/php.ini
RUN echo "max_input_time = 3000" >> /usr/local/etc/php/php.ini

RUN composer install --prefer-dist --no-scripts
COPY . /app
RUN composer dump-autoload

EXPOSE 8000
CMD /bin/sh -c "\
    if [ -f .env.$env_postfix ]; then mv .env.$env_postfix .env; else echo 'No env postfix file found'; fi && \
    service cron start && \
    php artisan queue:listen & \
    php artisan serve --host=0.0.0.0 --port=8000 \
"
