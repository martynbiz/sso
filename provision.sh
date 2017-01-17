#!/usr/bin/env bash

sudo apt-get update

# ========================================
# install apache

sudo apt-get install -y apache2

sudo a2enmod rewrite
sudo service apache2 restart


# ========================================
# install mysql

MYSQL_ROOT_PASSWORD="vagrant1"

# prevent the prompt screen from showing
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQL_ROOT_PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQL_ROOT_PASSWORD"

# install mysql server
sudo apt-get install -y mysql-server


# # ========================================
# # install redis
#
# sudo apt-get install -y redis-server


# ========================================
# install php

sudo apt-get install -y php libapache2-mod-php php-mcrypt php-mysql php-curl php-mbstring php-xml #php-redis


# ========================================
# setup virtual host

# create apache config
sudo bash -c 'cat <<EOT >>/etc/apache2/sites-available/sso.conf
<VirtualHost *:80>
    ServerName sso.vagrant
    DocumentRoot /var/www/sso/website/public

    SetEnv APPLICATION_ENV "development"

    <Directory /var/www/sso/website/public/>
        Options FollowSymLinks
        AllowOverride All
    </Directory>

    # Logging
    ErrorLog /var/log/apache2/sso-error.log
    LogLevel notice
    CustomLog /var/log/apache2/sso-access.log combined
</VirtualHost>
EOT
'

sudo a2ensite sso.conf
sudo service apache2 reload

# create databases
echo "create database sso_dev" | mysql -u root -p$MYSQL_ROOT_PASSWORD
echo "create database sso_test" | mysql -u root -p$MYSQL_ROOT_PASSWORD

# run migrations
cd /var/www/sso/website
vendor/bin/phinx migrate --environment development
vendor/bin/phinx migrate --environment testing

# create ssh keys
cd /var/www/sso/website
ssh-keygen -f storage/id_rsa -t rsa -N ''
sudo chmod g+r storage/id_rsa
