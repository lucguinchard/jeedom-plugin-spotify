#!/bin/bash

if [ -f /tmp/spotify_dependancy ]; then
   	echo "Installation en cours ..."
	exit 2
else
	if [ -f /var/www/html/plugins/spotify/ressources/spotify_version ]; then
   		echo "Installation ok"
		exit 0
    else
     	echo "Installation nok"
		exit 1
	fi
fi
  
  
  
  
  
  
  