#!/bin/bash

# Ruta al archivo de log
LOGFILE="/var/log/ngrok.log"

# Fecha y hora
echo "==== Ngrok iniciado el $(date) ====" >> $LOGFILE

# Ejecutar ngrok y redirigir el log de salida
ngrok http 8080 > /tmp/ngrok_output.log &

# Esperar unos segundos para que Ngrok genere la URL
sleep 5

# Extraer la URL del panel web de ngrok
URL=$(curl -s http://127.0.0.1:4040/api/tunnels | grep -oP '"public_url":"https://[^"]*' | cut -d'"' -f4)

# Guardar la URL en el archivo de log
echo "URL asignada por Ngrok: $URL" >> $LOGFILE
echo "==== Fin del log ====" >> $LOGFILE
