<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('projects.index', function ($trail) {
    $trail->push('Projects', route('projects.index'));
});

Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Dashboard', route('dashboard'));
});

Breadcrumbs::for('dashboard.projects.show', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Projects', route('dashboard.projects.show'));
});

Breadcrumbs::for('dashboard.published-datasets.show', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Published Datasets', route('dashboard.published-datasets.show'));
});

Breadcrumbs::for('projects.show', function ($trail, $project) {
    $trail->parent('dashboard.projects.show');
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

Breadcrumbs::for('projects.experiments.workflows.edit', function ($trail, $project, $experiment, $workflow) {
    $trail->parent('projects.experiments.show', $project, $experiment);
    $trail->push('workflows / edit / '.$workflow->name,
        route('projects.experiments.workflows.edit', [$project, $experiment, $workflow]));
});

Breadcrumbs::for('projects.experiments.workflows.create', function ($trail, $project, $experiment) {
    $trail->parent('projects.experiments.show', $project, $experiment);
    $trail->push('workflows / create', route('projects.experiments.workflows.create', [$project, $experiment]));
});

Breadcrumbs::for('projects.experiments.activities.show', function ($trail, $project, $experiment, $activity) {
    $trail->parent('projects.experiments.index', $project, $experiment);
    $trail->push('Process: '.$activity->name,
        route('projects.experiments.activities.show', [$project, $experiment, $activity]));
});

Breadcrumbs::for('projects.experiments.entities.show', function ($trail, $project, $experiment, $entity) {
    $trail->parent('projects.experiments.index', $project, $experiment);
    $trail->push('Sample: '.$entity->name,
        route('projects.experiments.entities.show', [$project, $experiment, $entity]));
});

Breadcrumbs::for('projects.activities.computations.index', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Computations', route('projects.activities.computations.index', [$project]));
});

Breadcrumbs::for('projects.activities.computations.show', function ($trail, $project, $activity) {
    $trail->parent('projects.activities.computations.index', $project);
    $trail->push($activity->name, route('projects.activities.computations.show', [$project, $activity]));
});

Breadcrumbs::for('projects.entities.index', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Samples', route('projects.entities.index', [$project]));
});

Breadcrumbs::for('projects.computations.entities.index', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Computations', route('projects.entities.index', [$project]));
});

Breadcrumbs::for('projects.entities.show', function ($trail, $project, $entity) {
    $trail->parent('projects.entities.index', $project);
    $trail->push($entity->name, route('projects.entities.show', [$project, $entity]));
});

Breadcrumbs::for('projects.computations.entities.show', function ($trail, $project, $entity) {
    $trail->parent('projects.computations.entities.index', $project);
    $trail->push($entity->name, route('projects.computations.entities.show', [$project, $entity]));
});

Breadcrumbs::for('projects.datasets.index', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Datasets', route('projects.datasets.index', [$project]));
});

Breadcrumbs::for('projects.datasets.show.overview', function ($trail, $project, $dataset) {
    $trail->parent('projects.datasets.index', $project);
    $trail->push($dataset->name, route('projects.datasets.show.overview', [$project, $dataset]));
});

Breadcrumbs::for('projects.datasets.edit', function ($trail, $project, $dataset) {
    $trail->parent('projects.datasets.index', $project);
    $trail->push($dataset->name, route('projects.datasets.edit', [$project, $dataset]));
});

Breadcrumbs::for('projects.datasets.files.edit', function ($trail, $project, $dataset) {
    $trail->parent('projects.datasets.edit', $project, $dataset);
    $trail->push("Files", route('projects.datasets.files.edit', [$project, $dataset]));
});

Breadcrumbs::for('projects.datasets.folders.show', function ($trail, $project, $dataset) {
    $trail->parent('projects.datasets.edit', $project, $dataset);
    $trail->push("Files", route('projects.datasets.files.edit', [$project, $dataset]));
});

// Project Breadcrumbs
Breadcrumbs::for('projects.workflows.index', function ($trail, $project) {
    $trail->parent('projects.show', $project);
    $trail->push('Workflows', route('projects.workflows.index', [$project]));
});

Breadcrumbs::for('projects.workflows.edit', function ($trail, $project, $workflow) {
    $trail->parent('projects.workflows.index', $project);
    $trail->push($workflow->name, route('projects.workflows.edit', [$project, $workflow]));
});

Breadcrumbs::for('projects.workflows.create', function ($trail, $project) {
    $trail->parent('projects.workflows.index', $project);
    $trail->push('Create', route('projects.workflows.create', [$project]));
});

Breadcrumbs::for('projects.workflows.show', function ($trail, $project, $workflow) {
    $trail->parent('projects.workflows.index', $project);
    $trail->push($workflow->name, route('projects.workflows.show', [$project, $workflow]));
});

// Create Dataset breadcrumbs
Breadcrumbs::for('projects.datasets.create', function ($trail, $project) {
    $trail->parent('projects.datasets.index', $project);
    $trail->push('Create', route('projects.datasets.create', [$project]));
});

Breadcrumbs::for('projects.datasets.create-data', function ($trail, $project, $dataset) {
    $trail->parent('projects.datasets.create', $project);
    $trail->push("{$dataset->name} Files", route('projects.datasets.create-data', [$project, $dataset]));
});

// Published data breadcrumbs

Breadcrumbs::for('public.datasets.index', function ($trail) {
    $trail->push('Datasets', route('public.datasets.index'));
});

Breadcrumbs::for('public.datasets.show', function ($trail, $dataset) {
    $trail->parent('public.datasets.index');
    $trail->push($dataset->name, route('public.datasets.show', [$dataset]));
});

Breadcrumbs::for('public.datasets.overview.show', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Overview', route('public.datasets.overview.show', [$dataset]));
});

Breadcrumbs::for('public.datasets.files.index', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Files', route('public.datasets.files.index', [$dataset]));
});

Breadcrumbs::for('public.datasets.workflows.index', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Workflows', route('public.datasets.workflows.index', [$dataset]));
});

Breadcrumbs::for('public.datasets.activities.index', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Processes', route('public.datasets.activities.index', [$dataset]));
});

Breadcrumbs::for('public.datasets.activities.show', function ($trail, $dataset, $activity) {
    $trail->parent('public.datasets.activities.index', $dataset);
    $trail->push($activity->name, route('public.datasets.activities.show', [$dataset, $activity]));
});

Breadcrumbs::for('public.datasets.entities.index', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Samples', route('public.datasets.entities.index', [$dataset]));
});

Breadcrumbs::for('public.datasets.entities.show', function ($trail, $dataset, $entity) {
    $trail->parent('public.datasets.entities.index', $dataset);
    $trail->push($entity->name, route('public.datasets.entities.show', [$dataset, $entity]));
});

Breadcrumbs::for('public.datasets.communities.index', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Communities', route('public.datasets.communities.index', [$dataset]));
});

Breadcrumbs::for('public.datasets.comments.index', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Comments', route('public.datasets.comments.index', [$dataset]));
});

Breadcrumbs::for('public.datasets.data-dictionary.entities', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Samples Data Dictionary', route('public.datasets.data-dictionary.entities', [$dataset]));
});

Breadcrumbs::for('public.datasets.data-dictionary.activities', function ($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Processes Data Dictionary', route('public.datasets.data-dictionary.activities', [$dataset]));
});

Breadcrumbs::for('public.datasets.files.show', function ($trail, $dataset, $file) {
    $trail->parent('public.datasets.folders.show', $dataset, $file->directory_id);
    $trail->push($file->name, route('public.datasets.folders.show', [$dataset, $file->directory_id]));
});

Breadcrumbs::for('public.datasets.folders.show', function($trail, $dataset) {
    $trail->parent('public.datasets.show', $dataset);
    $trail->push('Files', route('public.datasets.folders.show', [$dataset, -1]));
});
