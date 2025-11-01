# Use official PHP + Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite (important for MVC routing)
RUN a2enmod rewrite

# Install extensions (PDO MySQL, mysqli, etc.)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy project files to container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/public

# Copy custom PHP settings (optional)
COPY docker/php.ini /usr/local/etc/php/

# Set Apache document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache configuration
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
