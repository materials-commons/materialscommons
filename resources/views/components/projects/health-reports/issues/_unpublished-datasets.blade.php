@props(['healthReport'])
<div class="issue-section mb-3">
    <h6 class="text-warning">
        <i class="fas fa-database"></i>
        Unpublished Datasets with DOIs ({{ $healthReport->unpublishedDatasetsWithDOIs->count() }})
    </h6>
    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>Dataset</th>
                <th>DOI</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            @foreach($healthReport->unpublishedDatasetsWithDOIs->take(5) as $dataset)
                <tr>
                    <td>{{ $dataset['name'] ?? 'N/A' }}</td>
                    <td>
                        <small class="text-monospace">{{ $dataset['doi'] ?? 'N/A' }}</small>
                    </td>
                    <td>
                        <small>{{ isset($dataset['created_at']) ? \Carbon\Carbon::parse($dataset['created_at'])->diffForHumans() : 'N/A' }}</small>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if($healthReport->unpublishedDatasetsWithDOIs->count() > 5)
            <small class="text-muted">... and {{ $healthReport->unpublishedDatasetsWithDOIs->count() - 5 }} more datasets</small>
        @endif
    </div>
</div>
