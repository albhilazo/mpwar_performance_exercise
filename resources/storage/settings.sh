#!/bin/bash

sudo sed -i 's/bind 127.0.0.1/bind 0.0.0.0/g' /etc/redis/redis.conf
sudo sed -i "s/$(grep bind-address /etc/mysql/my.cnf)/bind-address = 0.0.0.0/g" /etc/mysql/my.cnf

sudo service mysql restart
sudo service redis-server restart
