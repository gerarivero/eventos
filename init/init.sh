#!/bin/bash

# Instalando dependencias con composer
composer install

# Poblando web/bundles
npm install ; npm audit fix
node_modules/.bin/encore prod

# Sincronizando con la base de datos
php bin/console doctrine:schema:validate

# Levantando apache
exec "apache2-foreground"