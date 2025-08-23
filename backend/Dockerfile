FROM php:8.2-apache

# Install basic PHP extensions we need
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy our Laravel app
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions (basic setup)
RUN chown -R www-data:www-data /var/www/html

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

