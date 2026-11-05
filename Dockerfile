# Imagen de la aplicación: PHP 8.3 + Apache
FROM php:8.3-apache

# - pdo_mysql: conexión a MySQL (mbstring ya viene incluido en la imagen).
# - mod_rewrite y AllowOverride All: el .htaccess del proyecto los necesita.
# - php.ini de producción: no muestra errores de PHP en pantalla.
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# El proyecto completo es la raíz del sitio (el .htaccess redirige hacia public/).
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -fsS http://localhost/public/api.php/health || exit 1