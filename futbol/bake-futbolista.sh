#!/bin/bash
url=$1
token=$(cat ~/cakeapp/futbol/config/token)
wget -qO- --no-check-certificate "${url}/Bets/futbolista/ws:1/token:$token" 2>/dev/null
