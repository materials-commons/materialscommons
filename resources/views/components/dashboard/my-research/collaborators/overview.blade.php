@props([
    'datasets' => collect(),
    'projects' => collect(),
])

@php
    $datasets = collect($datasets);
    $projects = collect($projects);
    $currentUserId = auth()->id();
    $currentUserName = auth()->user()?->name;

    $normalizeName = function ($name) {
        return trim((string) $name);
    };

    $datasetCollaborators = collect();

    foreach ($datasets as $dataset) {
        if (blank($dataset->ds_authors ?? null)) {
            continue;
        }

        foreach (collect($dataset->ds_authors) as $author) {
            $name = $normalizeName(data_get($author, 'name'));

            if (blank($name) || strcasecmp($name, (string) $currentUserName) === 0) {
                continue;
            }

            $datasetCollaborators->push([
                'key' => mb_strtolower($name),
                'name' => $name,
                'affiliations' => data_get($author, 'affiliations'),
                'relationship' => 'Dataset co-author',
                'source' => 'dataset',
                'dataset_id' => $dataset->id,
                'dataset_name' => $dataset->name,
                'dataset_project_id' => $dataset->project_id,
                'project_id' => $dataset->project?->id ?? $dataset->project_id,
                'project_name' => $dataset->project?->name,
                'is_published_dataset' => filled($dataset->published_at ?? null),
                'is_external' => true,
                'role' => 'Author',
            ]);
        }
    }

    $projectCollaborators = collect();

    foreach ($projects as $project) {
        $members = collect($project->team?->members ?? collect())->map(function ($user) use ($project, $currentUserId) {
            if ((int) ($user->id ?? 0) === (int) $currentUserId) {
                return null;
            }

            return [
                'key' => 'user:' . $user->id,
                'name' => $user->name ?? 'Unknown User',
                'email' => $user->email ?? null,
                'affiliations' => $user->affiliations ?? null,
                'relationship' => 'Project collaborator',
                'source' => 'project',
                'project_id' => $project->id,
                'project_name' => $project->name,
                'is_private_access' => true,
                'is_external' => false,
                'role' => 'Member',
            ];
        })->filter();

        $admins = collect($project->team?->admins ?? collect())->map(function ($user) use ($project, $currentUserId) {
            if ((int) ($user->id ?? 0) === (int) $currentUserId) {
                return null;
            }

            return [
                'key' => 'user:' . $user->id,
                'name' => $user->name ?? 'Unknown User',
                'email' => $user->email ?? null,
                'affiliations' => $user->affiliations ?? null,
                'relationship' => 'Project collaborator',
                'source' => 'project',
                'project_id' => $project->id,
                'project_name' => $project->name,
                'is_private_access' => true,
                'is_external' => false,
                'role' => 'Admin',
            ];
        })->filter();

        $projectCollaborators = $projectCollaborators->merge($members)->merge($admins);
    }

    $allCollaboratorEvents = $datasetCollaborators->merge($projectCollaborators);

    $collaborators = $allCollaboratorEvents
        ->groupBy('key')
        ->map(function ($events) {
            $datasets = $events
                ->where('source', 'dataset')
                ->filter(fn($event) => filled($event['dataset_id'] ?? null))
                ->unique('dataset_id')
                ->values();

            $projects = $events
                ->filter(fn($event) => filled($event['project_id'] ?? null))
                ->unique('project_id')
                ->values();

            $roles = $events
                ->pluck('role')
                ->filter()
                ->unique()
                ->values();

            $relationships = $events
                ->pluck('relationship')
                ->filter()
                ->unique()
                ->values();

            return [
                'key' => $events->first()['key'],
                'name' => $events->first()['name'],
                'email' => $events->pluck('email')->filter()->first(),
                'affiliations' => $events->pluck('affiliations')->filter()->first(),
                'relationships' => $relationships,
                'roles' => $roles,
                'datasets' => $datasets,
                'projects' => $projects,
                'dataset_count' => $datasets->count(),
                'published_dataset_count' => $datasets->where('is_published_dataset', true)->count(),
                'project_count' => $projects->count(),
                'private_project_count' => $projects->where('is_private_access', true)->count(),
                'is_external' => $events->contains('is_external', true) && !$events->contains('is_external', false),
                'score' => $datasets->count() + $projects->count(),
            ];
        })
        ->sortByDesc('score')
        ->values();

    $publishedDatasetCoAuthors = $collaborators->filter(fn($collaborator) => $collaborator['published_dataset_count'] > 0);
    $projectOnlyCollaborators = $collaborators->filter(fn($collaborator) => $collaborator['project_count'] > 0);
    $teamMembers = $projectOnlyCollaborators->filter(fn($collaborator) => $collaborator['roles']->contains('Member') || $collaborator['roles']->contains('Admin'));
    $frequentCollaborators = $collaborators->filter(fn($collaborator) => $collaborator['score'] >= 2);
    $privateAccessUsers = $collaborators->filter(fn($collaborator) => $collaborator['private_project_count'] > 0);
    $externalAuthors = $collaborators->filter(fn($collaborator) => $collaborator['is_external']);

    $datasetChartRows = $publishedDatasetCoAuthors
        ->sortByDesc('published_dataset_count')
        ->take(10)
        ->values();

    $projectChartRows = $projectOnlyCollaborators
        ->sortByDesc('project_count')
        ->take(10)
        ->values();
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.collaborators.summary-card
            label="Collaborators"
            :value="$collaborators->count()"
            hint="unique people"
            color="primary"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.collaborators.summary-card
            label="Published Co-authors"
            :value="$publishedDatasetCoAuthors->count()"
            hint="dataset authors"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.collaborators.summary-card
            label="Project Collaborators"
            :value="$projectOnlyCollaborators->count()"
            hint="project access"
            color="info"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.collaborators.summary-card
            label="Team Members"
            :value="$teamMembers->count()"
            hint="members/admins"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.collaborators.summary-card
            label="Frequent"
            :value="$frequentCollaborators->count()"
            hint="2+ links"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3 col-xl-2">
        <x-dashboard.my-research.collaborators.summary-card
            label="External Authors"
            :value="$externalAuthors->count()"
            hint="non-project users"
            color="danger"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-dashboard.my-research.collaborators.charts.dataset-coauthors
            :collaborators="$datasetChartRows"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-dashboard.my-research.collaborators.charts.project-collaborators
            :collaborators="$projectChartRows"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-7">
        <x-dashboard.my-research.collaborators.analytics
            :collaborators="$collaborators"
            :published-dataset-co-authors="$publishedDatasetCoAuthors"
            :project-collaborators="$projectOnlyCollaborators"
            :team-members="$teamMembers"
            :frequent-collaborators="$frequentCollaborators"
            :private-access-users="$privateAccessUsers"
            :external-authors="$externalAuthors"
        />
    </div>

    <div class="col-12 col-xl-5">
        <x-dashboard.my-research.collaborators.detail-panel
            :frequent-collaborators="$frequentCollaborators"
            :external-authors="$externalAuthors"
            :private-access-users="$privateAccessUsers"
        />
    </div>
</div>

<x-dashboard.my-research.collaborators.table
    :collaborators="$collaborators"
/>
