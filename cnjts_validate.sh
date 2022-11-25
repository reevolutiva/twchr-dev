#!/bin/bash
GREEN='\033[0;32m'
RED='\033[0;31m'
NOCOLOR='\033[0m'
echo -e "${GREEN}**************************************************************************************"
echo -e "cnjts_validate busca un texto dentro de una carpeta de archivos"
echo -e "cnjts_validate /Nombre_de_CARPETA_A_ANALIZAR/ Texto_a_analizar"
echo -e "**************************************************************************************${NOCOLOR}"
grep -rn --color=always --exclude-dir=.git $2 $1 >log_validate
cat log_validate
echo -e "${GREEN}**************************************************************************************"
echo -e "*** Análisis completado y almacenado en el archivo log_validate ***"
echo -e "${RED}*** Deseas borrar el archivo (s/n) ***"
read n
case $n in
n)
echo -e 'archivo log_validate creado'
;;
s)
rm log_validate
echo -e 'tu archivo ha sido borrado'
;;
*)
rm log_validate
echo -e 'por seguridad, tu archivo log ha sido borrado'
;;
esac
echo -e "${GREEN}*** FIN ***${NOCOLOR}"