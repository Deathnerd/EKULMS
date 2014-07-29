#!/bin/sh

sudo apt-get update
sudo apt-get upgrade
sudo apt-get install -y apache2 php5 mysql-server
#sudo rm -rf /var/www
#sudo ln -fs /vagrant /var/www
