FROM php:8.4-fpm

WORKDIR /var/www


RUN apt-get update && apt-get install -y \
    libwebp-dev \
    git \
    curl \
    build-essential \
    libzip-dev \
    libpng-dev libxpm-dev \
    libonig-dev \
    libmcrypt-dev \
    libicu-dev \
    libgd-dev \libjpeg62-turbo-dev \
    libfreetype6-dev \
    jpegoptim optipng pngquant gifsicle \
    locales \
    libxml2-dev \
    zip \
    unzip \
    net-tools \
    nano \
    telnetd \
    iputils-ping \
    tcpdump

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/*


RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-webp --with-jpeg

RUN pecl install redis && pecl install mongodb

#install php required extensions
RUN docker-php-ext-install pdo_mysql pdo mbstring exif pcntl ftp bcmath gd zip sockets intl

#enable installed extensions
RUN docker-php-ext-enable pdo_mysql pdo mbstring exif pcntl ftp bcmath gd zip  sockets redis intl mongodb


EXPOSE 9000

ENV DOCKER_HOST_USER_ID=1000

ENV DOCKER_HOST_GROUP_ID=1000

RUN groupadd -g 1000 www

RUN useradd -u 1000 -ms /bin/bash -g www www

USER www

CMD  php-fpm
