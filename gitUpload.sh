#!/bin/bash
RED='\033[0;31m'
NC='\033[0m' # No Color
WHITE='\033[1;37m' #bLANCO
### Ejecutar como gitUpload CarpetaDestino
#echo "################################### Clonar git "
#git clone https://github.com/escheinvar/coopelatina.git $1


echo "################################### Instalar composer "
#cd $1
composer install

echo "################################### Instalar npm"
npm install

echo "################################### Copiar manualmente archivo .env"
printf "${RED}scp .env root@200.58.106.175:$(pwd) ${WHITE}"
echo ""

echo "Luego de haber copiado, presiona cualquier tecla para seguir"
while [ true ] ; do
    read -t 3 -n 1
if [ $? = 0 ] ; then
    exit ;
else
    echo "presiona cualquier tecla para seguir"
fi
done

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