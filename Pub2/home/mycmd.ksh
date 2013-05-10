#!/bin/sh 
#echo "mycmd.ksh"
tail -15 ~/www/Pub2/errorlog.txt | awk '{print $0,"<br>"}'
#pwd
