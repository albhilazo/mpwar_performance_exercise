#!/bin/bash

sudo sed -i 's/bind 127.0.0.1/bind 0.0.0.0/g' /etc/redis/redis.conf
sudo sed -i "s/$(grep bind-address /etc/mysql/my.cnf)/bind-address = 0.0.0.0/g" /etc/mysql/my.cnf

sudo mysql -u root -e "CREATE USER IF NOT EXISTS 'mpwar'@'%' IDENTIFIED BY 'performance_pass'"
sudo mysql -u root -e "GRANT ALL PRIVILEGES ON mpwar_performance_blog.* TO 'mpwar'@'%'"

sudo service mysql restart
sudo service redis-server restart
