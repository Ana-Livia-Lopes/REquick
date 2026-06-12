FROM php:8.2-apache

# Instala extensões do PHP necessárias para o MySQL (PDO e mysqli)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilita o mod_rewrite do Apache (comum para rotas em PHP/JS)
RUN a2enmod rewrite

# Define o diretório de trabalho dentro do container
WORKDIR /var/www/html

# Expõe a porta padrão do Apache
EXPOSE 80