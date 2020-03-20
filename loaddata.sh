#!/bin/sh
> $HOME/load.out
php artisan migrate:fresh >> $HOME/load.out
php artisan mc:import-rethinkdb-data /home/gtarcea/dump/db/materialscommons --ignore-existing >> $HOME/load.out
