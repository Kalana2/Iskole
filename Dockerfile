# Use official PHP + Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite (important for MVC routing)
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set Apache document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy dev php.ini (disables opcache for instant refresh in dev)
COPY docker/php.ini /usr/local/etc/php/conf.d/dev.ini

# Copy project files
COPY . /var/www/html/

# Workdir
WORKDIR /var/www/html/public
