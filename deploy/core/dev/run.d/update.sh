#!/bin/bash
if [ ! -f composer.lock ]; then
  mv index.php index.old.php
  echo "<html><head></head><body><h1>dwp</h1><p>Descargando WP</p><p>dwp logs: Para más información</p></body></html>" > index.php
  composer install
  mv index.old.php index.php
fi
