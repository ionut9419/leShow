#!/bin/bash

cron -f &
php-fpm --allow-to-run-as-root
