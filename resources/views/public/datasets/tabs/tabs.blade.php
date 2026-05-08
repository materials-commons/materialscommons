<div class="card border-0 shadow-sm mb-3" style="border-radius:.75rem;">
    <div class="card-body p-2 background-white">
        <div class="d-flex flex-wrap gap-1">
            <a class="btn btn-sm px-3 {{ setActiveNavByName('public.datasets.overview') ? 'btn-primary' : 'btn-light border' }}"
               href="{{ route('public.datasets.overview.show', ['dataset' => $dataset]) }}">
                <i class="fas fa-home me-1"></i>
                Overview
            </a>

            <a class="btn btn-sm px-3 {{ setActiveNavByName('public.datasets.folders') ? 'btn-primary' : 'btn-light border' }}"
               href="{{ route('public.datasets.folders.show', [$dataset, '-1']) }}">
                <i class="fas fa-folder-open me-1"></i>
                Files
                <span class="badge rounded-pill bg-secondary ms-1">{{ number_format($dataset->files_count) }}</span>
            </a>

            <a class="btn btn-sm px-3 {{ setActiveNavByName('public.datasets.entities') ? 'btn-primary' : 'btn-light border' }}"
               href="{{ route('public.datasets.entities.index', ['dataset' => $dataset]) }}">
                <i class="fas fa-cube me-1"></i>
                Samples
                <span class="badge rounded-pill bg-secondary ms-1">{{ number_format($dataset->entities_count) }}</span>
            </a>

            <a class="btn btn-sm px-3 {{ setActiveNavByName('public.datasets.workflows') ? 'btn-primary' : 'btn-light border' }}"
               href="{{ route('public.datasets.workflows.index', ['dataset' => $dataset]) }}">
                <i class="fas fa-project-diagram me-1"></i>
                Workflows
                <span class="badge rounded-pill bg-secondary ms-1">{{ number_format($dataset->workflows_count) }}</span>
            </a>

            <a class="btn btn-sm px-3 {{ setActiveNavByName('public.datasets.communities') ? 'btn-primary' : 'btn-light border' }}"
               href="{{ route('public.datasets.communities.index', [$dataset]) }}">
                <i class="fas fa-users me-1"></i>
                Communities
                <span class="badge rounded-pill bg-secondary ms-1">{{ number_format($dataset->communities_count) }}</span>
            </a>

            <a class="btn btn-sm px-3 {{ setActiveNavByName('public.datasets.comments') ? 'btn-primary' : 'btn-light border' }}"
               href="{{ route('public.datasets.comments.index', ['dataset' => $dataset]) }}">
                <i class="fas fa-comments me-1"></i>
                Comments
                <span class="badge rounded-pill bg-secondary ms-1">{{ number_format($dataset->comments_count) }}</span>
            </a>
        </div>
    </div>
</div>
