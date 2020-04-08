#!/bin/sh
> $HOME/load.out
php artisan migrate >> $HOME/load.out
MCFILE=$(ls /mcfs/gtarcea/dumps | tail -1)
rm -rf /home/gtarcea/dump/*
cp /mcfs/gtarcea/dumps/${MCFILE} /home/gtarcea/dump
(cd /home/gtarcea/dump; tar zxf *.gz)
(cd /home/gtarcea/dump; rm *.gz)
(cd /home/gtarcea/dump; mv *dump* db)

php artisan mc:import-rethinkdb-data /home/gtarcea/dump/db/materialscommons --ignore-existing >> $HOME/load.out
