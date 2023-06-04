#!/bin/sh
# Update DB for Commerce 


# define database connectivity
_db="DB_Telegram"
_db_user="USERXX"
_db_password="PASSWORDXX"

# define directory containing CSV files
_csv_directory_registryCommerce="/var/lib/mysql-files/commerce_registry.CSV"

 
# remove old file csv
rm $_csv_directory_registryCommerce

echo "\n \n"

# download file csv
echo "DOWNLOAD file CSV Per il commercio \n \n"
sleep 5

wget -O $_csv_directory_registryCommerce --user-agent "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:11.0) Gecko/20100101 Firefox/11.0" "http://udinebot.homepc.it/commerce.csv"
echo "\n \n"

# script for update DB registry Pharm

echo "Update DB for REGISTRY CSV from Open Street Map \n \n"
mysqlimport --ignore-lines=2 --fields-terminated-by=';' --fields-enclosed-by='"' --lines-terminated-by="\n" -u $_db_user -p$_db_password $_db $_csv_directory_registryCommerce --delete
echo "\n END UPDATE REGISTRY COMMERC \n \n \n"
