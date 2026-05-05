@props([
    'paperRows' => collect(),
    'datasetsWithoutPapers' => collect(),
    'datasetsReadyToPublishButNotPublic' => collect(),
    'datasetsWithPublicationMetadataIncomplete' => collect(),
])

@php
    $paperRows = collect($paperRows);
    $papersMissingDoi = $paperRows->filter(fn($row) => $row['missing']->contains('DOI'));
    $datasetsWithoutPapers = collect($datasetsWithoutPapers);
    $datasetsReadyToPublishButNotPublic = collect($datasetsReadyToPublishButNotPublic);
    $datasetsWithPublicationMetadataIncomplete = collect($datasetsWithPublicationMetadataIncomplete);

    $attentionItems = collect();

    foreach ($papersMissingDoi as $row) {
        $attentionItems->push([
            'type' => 'Paper',
            'label' => $row['paper']->name,
            'issue' => 'Missing DOI',
            'priority' => 'High',
            'url' => null,
        ]);
    }

    foreach ($datasetsWithoutPapers as $dataset) {
        $attentionItems->push([
            'type' => 'Dataset',
            'label' => $dataset->name,
            'issue' => 'No associated paper',
            'priority' => filled($dataset->published_at ?? null) ? 'High' : 'Medium',
            'url' => route('projects.datasets.show', [$dataset->project_id, $dataset->id]),
        ]);
    }

    foreach ($datasetsReadyToPublishButNotPublic as $dataset) {
        $attentionItems->push([
            'type' => 'Dataset',
            'label' => $dataset->name,
            'issue' => 'Ready to publish but not public',
            'priority' => 'Medium',
            'url' => route('projects.datasets.show', [$dataset->project_id, $dataset->id]),
        ]);
    }

    foreach ($datasetsWithPublicationMetadataIncomplete as $dataset) {
        $attentionItems->push([
            'type' => 'Dataset',
            'label' => $dataset->name,
            'issue' => 'Publication metadata incomplete',
            'priority' => 'Medium',
            'url' => route('projects.datasets.show', [$dataset->project_id, $dataset->id]),
        ]);
    }

    $attentionItems = $attentionItems
        ->unique(fn($item) => $item['type'] . ':' . $item['label'] . ':' . $item['issue'])
        ->take(8)
        ->values();
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <h6 class="card-title text-muted mb-1">
            <i class="fas fa-exclamation-triangle me-1"></i>Publication Items Needing Attention
        </h6>

        <p class="text-muted mb-3" style="font-size:.85rem;">
            Papers and datasets that may block citation quality or publication readiness.
        </p>

        @forelse($attentionItems as $item)
            <div class="border rounded p-2 mb-2">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="min-width-0">
                        <div class="fw-semibold text-truncate">
                            @if(filled($item['url']))
                                <a href="{{ $item['url'] }}" class="text-decoration-none">
                                    {{ $item['label'] }}
                                </a>
                            @else
                                {{ $item['label'] }}
                            @endif
                        </div>

                        <div class="text-muted" style="font-size:.8rem;">
                            {{ $item['type'] }} · {{ $item['issue'] }}
                        </div>
                    </div>

                    @if($item['priority'] === 'High')
                        <span class="badge text-bg-danger">High</span>
                    @else
                        <span class="badge text-bg-warning">Medium</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-muted text-center py-4">
                No publication issues found.
            </div>
        @endforelse
    </div>
</div>
