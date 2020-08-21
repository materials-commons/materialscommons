#!/bin/sh
cp .env.sqlite .env
php artisan migrate:fresh --seed
podman build -t docker.io/materialscommons/materialscommons-dev .
podman push docker.io/materialscommons/materialscommons-dev
cp .env.mysql .env

