#!/bin/bash

# Mise à jour du système
sudo apt update
sudo apt upgrade -y

# Installation des paquets nécessaires
sudo apt install -y apache2 php phpmyadmin mysql-server git python3 python3-pip python3-venv

# Cloner le site web depuis GitHub
GITHUB_REPO_URL="https://github.com/Yuraas2001/Thales" # Remplacer par l'URL de votre dépôt
WEB_DIR="/var/www/html"
TEMP_DIR="/tmp/web_temp"

# Supprimer le contenu actuel du répertoire web et cloner le dépôt
sudo rm -rf $WEB_DIR/*
git clone $GITHUB_REPO_URL $TEMP_DIR

# Copier le contenu cloné dans le répertoire web
sudo cp -r $TEMP_DIR/* $WEB_DIR/
sudo chown -R www-data:www-data $WEB_DIR
sudo chmod -R 755 $WEB_DIR

# Importer la base de données SQL dans MySQL
DB_NAME="BP"
SQL_FILE="$WEB_DIR/BP(2).sql" # Remplacer par le chemin de votre fichier SQL si différent

# Créer la base de données
sudo mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME"

# Importer le fichier SQL dans la base de données
if [ -f $SQL_FILE ]; then
    sudo mysql $DB_NAME < $SQL_FILE
else
    echo "Fichier SQL introuvable: $SQL_FILE"
fi

# Nettoyage du répertoire temporaire
rm -rf $TEMP_DIR

# Créer un environnement virtuel Python et installer les dépendances
PYTHON_ENV_DIR="$WEB_DIR/myenv"

# Créer l'environnement virtuel
python3 -m venv $PYTHON_ENV_DIR

# Activer l'environnement virtuel et installer les paquets Python
source $PYTHON_ENV_DIR/bin/activate
pip install mysql-connector-python openpyxl reportlab
deactivate

# Configurer Apache pour utiliser PHPMyAdmin (optionnel, si nécessaire)
sudo ln -s /usr/share/phpmyadmin /var/www/html/phpmyadmin

# Redémarrer Apache pour appliquer les modifications
sudo systemctl restart apache2

# Afficher un message de succès
echo "Installation et configuration terminées avec succès!"
