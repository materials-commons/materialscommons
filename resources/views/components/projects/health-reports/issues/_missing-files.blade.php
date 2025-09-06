@props(['files'])
<div class="issue-section mb-3">
    <h6 class="text-danger">
        <i class="fas fa-file-times"></i>
        Missing Files ({{ $files->count() }})
    </h6>
    <p>These files appear in the database, but the actual files are missing from disk.</p>
    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>File Name</th>
                <th>Path</th>
                <th>Size</th>
                <th>Last Modified</th>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $file)
                <tr>
                    <td>{{ $file['name'] ?? 'N/A' }}</td>
                    <td>
                        <small class="text-muted">{{ $file['path'] ?? 'N/A' }}</small>
                    </td>
                    <td>{{ isset($file['size']) ? number_format($file['size']) . ' bytes' : 'N/A' }}</td>
                    <td>
                        <small>{{ isset($file['updated_at']) ? \Carbon\Carbon::parse($file['updated_at'])->diffForHumans() : 'N/A' }}</small>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
