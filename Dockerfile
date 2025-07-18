# Dockerfile
FROM php:8.2-apache

# Install mysqli
RUN docker-php-ext-install mysqli

# Optional: Enable other extensions like pdo_mysql
RUN docker-php-ext-install pdo_mysql

# Copy your source code
COPY . /var/www/html/

FROM php:8.1-apache
COPY . /var/www/html/
EXPOSE 80
