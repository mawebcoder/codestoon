FROM php:8.4-fpm

WORKDIR /var/www

# Install required dependencies
RUN apt-get update && apt-get install -y \
    libwebp-dev \
    git \
    curl \
    build-essential \
    autoconf \
    pkg-config \
    libssl-dev \
    libzip-dev \
    libpng-dev \
    libxpm-dev \
    libonig-dev \
    libgd-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    locales \
    libxml2-dev \
    zip \
    unzip \
    net-tools \
    nano \
    supervisor \
    telnetd \
    iputils-ping \
    tcpdump \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure GD extension
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-webp --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    pdo \
    mbstring \
    exif \
    pcntl \
    ftp \
    bcmath \
    gd \
    zip \
    sockets \
    intl \
    opcache

RUN pecl install redis && pecl install mongodb

RUN docker-php-ext-enable mongodb redis

# Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/custom/supervisord.conf"]
