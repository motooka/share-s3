#!/bin/bash

set -e

# install dependencies
cd /repository/webapp
yes | composer install

# set permissions
cd /repository/webapp/bin
chmod 755 cake

