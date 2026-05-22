#!/bin/bash
cd /home/pagviva/htdocs/pagviva.com || exit 1
/usr/bin/php artisan queue:work \
  --sleep=3 \
  --tries=3 \
  --timeout=90 \
  --memory=256
