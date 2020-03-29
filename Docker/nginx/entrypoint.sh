#!/bin/sh

if [ ! -d "/var/www1" ]; then
   mkdir /var/www1
fi
cd /var/www1
if [ ! -d "videoserver" ]; then
   echo "CREATING VIDEOSERVER"
   wget https://codeload.github.com/gymdb/videoserver/zip/master -O /var/www1/master.zip
   unzip -o /var/www1/master.zip 
   rm /var/www1/master.zip
   mv /var/www1/videoserver-master /var/www1/videoserver
   mkdir videoserver/data/videos/OS -p
   mkdir videoserver/data/videos/US
   mkdir videoserver/data/videos/1
   mkdir videoserver/data/videos/2
   mkdir videoserver/data/videos/3
   mkdir videoserver/data/videos/4
   mkdir videoserver/data/videos/5
   mkdir videoserver/data/videos/6
   mkdir videoserver/data/videos/7
   mkdir videoserver/data/videos/8         
   chmod 777 videoserver/data -R   
   echo -e "<?php header(\"Location: videoserver/\");?>" >/var/www1/index.php
   echo -e "<?php return; ?>\n[SQL]\nhost = database\nuser = root\npassword = docker\ndbname = media"  >/var/www1/videoserver/settings.ini.php
fi 


exec "$@"
