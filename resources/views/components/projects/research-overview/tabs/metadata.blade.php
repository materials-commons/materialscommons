@props([
    'project',
    'readme' => null,
])

@php
    $hasReadme = filled($readme);
    $hasDescription = filled($project->description ?? null) || filled($project->summary ?? null);
    $entityAttributesCount = (int) ($project->entityAttributesCount ?? 0);
    $activityAttributesCount = (int) ($project->activityAttributesCount ?? 0);
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-3 background-white">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="card-title text-muted mb-1">
                    <i class="fas fa-clipboard-check me-1"></i>Metadata Readiness
                </h6>
                <p class="text-muted mb-0">
                    Project README, description, dataset metadata, sample attributes,
                    process attributes, and publishing readiness checks.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('projects.data-dictionary.entities', [$project]) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-cubes me-1"></i>Sample Attributes
                </a>

                <a href="{{ route('projects.data-dictionary.activities', [$project]) }}"
                   class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-cogs me-1"></i>Process Attributes
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Suggested Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>README</td>
                    <td>Project</td>
                    <td>
                        <span class="badge text-bg-{{ $hasReadme ? 'success' : 'warning' }}">
                            {{ $hasReadme ? 'Present' : 'Missing' }}
                        </span>
                    </td>
                    <td>{{ $hasReadme ? 'Low' : 'Medium' }}</td>
                    <td class="text-muted">
                        {{ $hasReadme ? 'Keep README current as the project evolves.' : 'Add a README to describe the project.' }}
                    </td>
                </tr>

                <tr>
                    <td>Description</td>
                    <td>Project</td>
                    <td>
                        <span class="badge text-bg-{{ $hasDescription ? 'success' : 'warning' }}">
                            {{ $hasDescription ? 'Present' : 'Missing' }}
                        </span>
                    </td>
                    <td>{{ $hasDescription ? 'Low' : 'Medium' }}</td>
                    <td class="text-muted">
                        {{ $hasDescription ? 'Project description is available.' : 'Add a project description or summary.' }}
                    </td>
                </tr>

                <tr>
                    <td>Sample Attributes</td>
                    <td>Data Dictionary</td>
                    <td>
                        <span class="badge text-bg-{{ $entityAttributesCount > 0 ? 'success' : 'secondary' }}">
                            {{ number_format($entityAttributesCount) }} defined
                        </span>
                    </td>
                    <td>{{ $entityAttributesCount > 0 ? 'Low' : 'Medium' }}</td>
                    <td>
                        <a href="{{ route('projects.data-dictionary.entities', [$project]) }}">
                            Review sample attributes
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>Process Attributes</td>
                    <td>Data Dictionary</td>
                    <td>
                        <span class="badge text-bg-{{ $activityAttributesCount > 0 ? 'success' : 'secondary' }}">
                            {{ number_format($activityAttributesCount) }} defined
                        </span>
                    </td>
                    <td>{{ $activityAttributesCount > 0 ? 'Low' : 'Medium' }}</td>
                    <td>
                        <a href="{{ route('projects.data-dictionary.activities', [$project]) }}">
                            Review process attributes
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>Dataset Metadata</td>
                    <td>Publishing</td>
                    <td><span class="badge text-bg-secondary">Placeholder</span></td>
                    <td>Medium</td>
                    <td class="text-muted">
                        Placeholder for licenses, DOIs, authors, descriptions, and tags.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
