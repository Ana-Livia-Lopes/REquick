FROM php:8.2-apache

# Instala as extensões do PHP necessárias para o banco
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilita o mod_rewrite do Apache
RUN a2enmod rewrite

# Expõe a porta 80 do Apache
EXPOSE 80
