# Use official PHP image with Apache
FROM php:8.2-apache

# Enable mysqli extension
RUN docker-php-ext-install mysqli

# Copy app code to web root
COPY . /var/www/html/

# Set file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
