FROM php:8.0.25-cli

RUN apt-get update && apt-get install -y \
    git \   
    zip \
    curl \
    unzip 
    
RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer