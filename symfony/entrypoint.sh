#!/bin/bash

rm -rf /var/www/symfony2/var/cache/*
su -m r -c "$*"
