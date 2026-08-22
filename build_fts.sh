#!/usr/bin/env bash

php artisan scout:sync-index-settings
php artisan scout:import "App\Models\Entity"
php artisan scout:import "App\Models\File"
php artisan scout:import "App\Models\Dataset"
php artisan scout:import "App\Models\Experiment"
php artisan scout:import "App\Models\Activity"
php artisan scout:import "App\Models\Community"
php artisan scout:import "App\Models\Project"
