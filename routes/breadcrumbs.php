<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('projects.index', function ($trail) {
    $trail->push('Projects', route('projects.index'));
});

Breadcrumbs::for('projects.show', function ($trail, $project) {
    $trail->parent('projects.index');
    $trail->push($project->name, route('projects.show', [$project]));
});

Breadcrumbs::for('projects.experiments.index', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Experiments', route('projects.experiments.index', [$project]));
});

Breadcrumbs::for('projects.experiments.show', function ($trail, $project, $experiment) {
    $trail->parent('projects.experiments.index', $project);
    $trail->push($experiment->name, route('projects.experiments.show', [$project, $experiment]));
});

Breadcrumbs::for('projects.experiments.activities.show', function ($trail, $project, $experiment, $activity) {
    $trail->parent('projects.experiments.index', $project, $experiment);
    $trail->push('Process: '.$activity->name,
        route('project.experiments.activities.show', [$project, $experiment, $activity]));
});

Breadcrumbs::for('projects.experiments.entities.show', function ($trail, $project, $experiment, $entity) {
    $trail->parent('projects.experiments.index', $project, $experiment);
    $trail->push('Sample: '.$entity->name,
        route('project.experiments.entities.show', [$project, $experiment, $entity]));
});

Breadcrumbs::for('projects.activities.index', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Processes', route('projects.activities.index', [$project]));
});

Breadcrumbs::for('projects.activities.show', function ($trail, $project, $activity) {
    $trail->parent('projects.activities.index', $project);
    $trail->push($activity->name, route('projects.activities.show', [$project, $activity]));
});

Breadcrumbs::for('projects.entities.index', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Samples', route('projects.entities.index', [$project]));
});

Breadcrumbs::for('projects.entities.show', function ($trail, $project, $entity) {
    $trail->parent('projects.entities.index', $project);
    $trail->push($entity->name, route('projects.entities.show', [$project, $entity]));
});