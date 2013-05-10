#!/usr/local/bin/python
import sys, os, user

# sys.path.insert(0, "/usr/lib/python2.4")
sys.path.insert(0, "/home/artmarie/django")
sys.path.insert(0, "/home/artmarie/django/projects")
sys.path.insert(0, "/home/artmarie/django/projects/newproject")

# Switch to the directory of your project.
os.chdir("/home/artmarie/django/projects/newproject")

# Set the DJANGO_SETTINGS_MODULE environment variable.
os.environ['DJANGO_SETTINGS_MODULE'] = "newproject.settings"

from django.core.servers.fastcgi import runfastcgi
runfastcgi(method="threaded", daemonize="false")
