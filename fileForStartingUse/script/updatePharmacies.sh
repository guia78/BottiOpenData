#!/bin/sh
# Update DB for Pharmacies http://www.dati.salute.gov.it/dati/
# Update May 2023

# define database connectivity
# SETTING this value
_db="DB_Telegram"


# Define directory containing CSV files
# The name of table is egual at the name CSV
_csv_directory_registryPharm="/var/lib/mysql-files/Ext_pharmacies_registry.CSV"
_csv_directory_registryParaPharm="/var/lib/mysql-files/Ext_paraPharmacies_registry.CSV"

 
# Remove old file csv
rm $_csv_directory_registryPharm
rm $_csv_directory_registryParaPharm

echo "\n \n"

# Download file csv
echo "DOWNLOAD file CSV from site Ministero della salute \n \n"
sleep 5

wget -O $_csv_directory_registryPharm --user-agent "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20210101 Firefox/111.0" "http://www.dati.salute.gov.it/imgs/C_17_dataset_5_download_itemDownload0_upFile.CSV"
wget -O $_csv_directory_registryParaPharm --user-agent "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20210101 Firefox/111.0" "http://www.dati.salute.gov.it/imgs/C_17_dataset_7_download_itemDownload0_upFile.CSV"
echo "\n \n"

# Script for update DB registry Pharm

echo "Update DB for REGISTRY CSV from site Ministero Salute Farmacie \n \n"
mysqlimport --defaults-extra-file=myoptions.ini --ignore-lines=2 --fields-terminated-by=';' --lines-terminated-by="\n" $_db $_csv_directory_registryPharm --delete
echo "\n END UPDATE REGISTRY PHARM \n \n \n"

# Script for update DB registry Pharm

echo "Update DB for REGISTRY CSV from site Ministero Salute ParaFarmacie \n \n"
mysqlimport --defaults-extra-file=myoptions.ini --ignore-lines=2 --fields-terminated-by=';' --lines-terminated-by="\n" $_db $_csv_directory_registryParaPharm --delete
echo "\n END UPDATE REGISTRY \n \n \n"