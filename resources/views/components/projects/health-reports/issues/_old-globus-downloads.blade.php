@props(['healthReport'])
<div class="issue-section mb-3">
    <h6 class="text-warning">
        <i class="fas fa-download"></i>
        Old Globus Downloads ({{ $healthReport->oldGlobusDownloads->count() }})
    </h6>
    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>Download</th>
                <th>Age</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($healthReport->oldGlobusDownloads->take(5) as $download)
                <tr>
                    <td>{{ $download['name'] ?? 'N/A' }}</td>
                    <td>
                        <small>{{ isset($download['created_at']) ? \Carbon\Carbon::parse($download['created_at'])->diffForHumans() : 'N/A' }}</small>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $download['status'] ?? 'Unknown' }}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if($healthReport->oldGlobusDownloads->count() > 5)
            <small class="text-muted">... and {{ $healthReport->oldGlobusDownloads->count() - 5 }} more downloads</small>
        @endif
    </div>
</div>
