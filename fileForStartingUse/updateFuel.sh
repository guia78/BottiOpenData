#!/bin/sh
# Update DB for Fuel https://www.mimit.gov.it/index.php/it/open-data/elenco-dataset/carburanti-prezzi-praticati-e-anagrafica-degli-impianti
# Update May 2023

# define database connectivity
_db="DB_Telegram"
_db_user="USERXX"
_db_password="PASSWORDXX"

# define directory containing CSV files
_csv_directory_price="/var/lib/mysql-files/Ext_gasoline_price.csv"
_csv_directory_registry="/var/lib/mysql-files/Ext_gasoline_registry.csv"
 
# remove old file csv
rm $_csv_directory_price
rm $_csv_directory_registry

echo "\n \n"

# download file csv
echo "DOWNLOAD file CSV from site Ministero delle Imprese e del Made in Italy \n \n"
sleep 5

wget -O $_csv_directory_price --user-agent "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:11.0) Gecko/20100101 Firefox/50.0" "https://www.mimit.gov.it/images/exportCSV/prezzo_alle_8.csv"
wget -O $_csv_directory_registry --user-agent "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:11.0) Gecko/20100101 Firefox/50.0" "https://www.mimit.gov.it/images/exportCSV/anagrafica_impianti_attivi.csv"

echo "\n \n"

# script for update DB registry and price
echo "Update DB for PRICE CSV from site Sviluppo economico \n \n"
mysqlimport --columns="idImpianto,descCarburante,prezzo,isSelf,@dtComu) SET dtComu = (str_to_date(@dtComu, '%d/%m/%Y %H:%i')"  --ignore-lines=2 --fields-terminated-by=';' --lines-terminated-by="\n" -u $_db_user -p$_db_password $_db $_csv_directory_price --delete
sleep 5

echo "\n END UPDATE PRICE"
echo "Update DB for REGISTRY CSV from site Ministero delle Imprese e del Made in Italy \n \n"
mysqlimport --ignore-lines=2 --fields-terminated-by=';' --lines-terminated-by="\n" -u $_db_user -p$_db_password $_db $_csv_directory_registry --delete
echo "\n END UPDATE REGISTRY \n \n \n"


