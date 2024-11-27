#!/bin/bash

# Redirige la salida del comando a un archivo de log
cd /var/www/html/apcr-api/PROYECTO-API # Cambia esta ruta a la de tu proyecto
sudo php spark serve --host 0.0.0.0 --port 8080 > /var/log/spark.log 2>&1 &