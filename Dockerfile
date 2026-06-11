FROM php:8.1-apache

# Aktifkan mod_rewrite Apache untuk menangani file .htaccess
RUN a2enmod rewrite

# Perbarui paket OS dan pasang pustaka inti yang dibutuhkan untuk PHP
# (Termasuk kebutuhan pemrosesan file PDF dari tcpdf dan File ZIP)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Konfigurasi dan Instalasi Ekstensi PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli gd zip

# Deklarasikan direktori utama web server
WORKDIR /var/www/html

# Ganti kepemilikan folder (*Permissions*) untuk keamanan fitur 'Uploads'
RUN chown -R www-data:www-data /var/www/html
