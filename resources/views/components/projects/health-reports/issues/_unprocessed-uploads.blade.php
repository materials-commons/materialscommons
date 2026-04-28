@props(['healthReport'])
<div class="issue-section mb-3">
    <h6 class="text-info">
        <i class="fas fa-upload"></i>
        Unprocessed Globus Uploads ({{ $healthReport->unprocessedGlobusUploads->count() }})
    </h6>
    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>Upload</th>
                <th>Age</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($healthReport->unprocessedGlobusUploads->take(5) as $upload)
                <tr>
                    <td>{{ $upload['name'] ?? 'N/A' }}</td>
                    <td>
                        <small>{{ isset($upload['created_at']) ? \Carbon\Carbon::parse($upload['created_at'])->diffForHumans() : 'N/A' }}</small>
                    </td>
                    <td>
                        <span class="badge text-bg-info">{{ $upload['status'] ?? 'Pending' }}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if($healthReport->unprocessedGlobusUploads->count() > 5)
            <small class="text-muted">... and {{ $healthReport->unprocessedGlobusUploads->count() - 5 }} more uploads</small>
        @endif
    </div>
</div>
