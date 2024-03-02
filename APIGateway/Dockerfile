FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
USER root
RUN apt-get --allow-releaseinfo-change update && apt-get install -y \
    locales \
    curl \ 
    nano \
    cron

# Install extensions
RUN docker-php-ext-install pdo_mysql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -fg 1000 www
RUN id -u 1000 >/dev/null 2>&1 || useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
