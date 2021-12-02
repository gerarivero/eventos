FROM php:7.2.16-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends curl \
    wget \
# agregado para que funcione composer install
    zlib1g-dev git \
# instalando libreria memcached
    libmemcached-dev \
# instalando librerias para wktoimage
    libxrender1 libfontconfig1 libxext6 fontconfig libjpeg62-turbo xfonts-75dpi xfonts-base \
#
    libicu-dev

# instalando node para webpack
RUN rm -rf /var/lib/apt/lists/ && curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs

RUN a2enmod rewrite

COPY ./init/php.ini $PHP_INI_DIR/php.ini
COPY ./init/vhost.conf /etc/apache2/sites-enabled/000-default.conf

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html/

# Instala extenciones de php
RUN docker-php-ext-install zip mysqli pdo pdo_mysql intl

# Instalando wkhtmltopdf
RUN wget "https://downloads.wkhtmltopdf.org/0.12/0.12.5/wkhtmltox_0.12.5-1.stretch_amd64.deb" && apt-get install -y ./wkhtmltox_0.12.5-1.stretch_amd64.deb 

# Construyendo el proyecto
COPY ./init/init.sh /usr/local/bin/init.sh