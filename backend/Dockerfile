FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    iputils-ping \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    procps \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring zip pdo pdo_mysql pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application files
# COPY . .

# Set permissions for storage and cache
# RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
# RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

CMD ["php-fpm"]
