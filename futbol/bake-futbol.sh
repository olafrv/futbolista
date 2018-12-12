#!/bin/bash
export PATH=$PATH:~/cakephp/cake/console
chmod +x ~/cakephp/cake/console/cake
cake -webroot ../../httpdocs/futbol futbol $1
