#!/bin/bash
url=$1
token=$(cat ~/cakeapp/futbol/config/token)
wget -qO- --no-check-certificate "${url}/Mails/send/ws:1/token:$token" 2>&1 >/dev/null
