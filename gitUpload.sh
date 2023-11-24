#!/bin/bash
RED='\033[0;31m'
NC='\033[0m' # No Color
WHITE='\033[1;37m' #bLANCO
### Ejecutar como gitUpload CarpetaDestino


echo "################################### Clonar git "
git clone https://github.com/escheinvar/coopelatina.git 


echo "################################### Instalar composer "
composer install

echo "################################### Instalar npm"
npm install

echo "################################### Copiar manualmente archivo .env"
printf "${RED}scp .env root@200.58.106.175:$(pwd) ${WHITE}"
echo ""

echo "################################### Generar clave de encriptación"
php artisan key:generate

echo "################################### Migrar base"
php artisan migrate

echo "################################### Generar Seeds"
php artisan db:seed

echo "################################### Generar liga de archivos"
php artisan storage:link 

echo "################################### Ejecutar Servidor"
php artisan serve --host=200.58.106.175



#######################SUPERVISOR
sudo apt-get install supervisor

cd /etc/supervisor/conf.d/

nano coope.conf

-----------------------
[program:coope]
command=php /home/enrique/coopelatina/artisan serve --host=200.58.106.175
autostart=true
autorestart=true
user=enrique
------------------------------
sudo supervisorctl reread
sudo supervisorctl update

sudo supervisorctl start coope