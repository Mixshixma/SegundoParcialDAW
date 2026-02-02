# Usamos una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos la extensión PDO MySQL para que PHP pueda hablar con Aiven
RUN docker-php-ext-install pdo pdo_mysql

# Copiamos todo el contenido de la carpeta backend al servidor
COPY . /var/www/html/

# Le decimos a Apache que escuche en el puerto que Render le asigne
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

RUN chown -R www-data:www-data /var/www/html

# Habilitamos el módulo de reescritura de Apache
RUN a2enmod rewrite


EXPOSE ${PORT}
