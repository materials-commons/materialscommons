@php use Carbon\Carbon; @endphp
@props(['files'])
<div class="issue-section mb-3">
    <p><i class="fas fa-exclamation-triangle text-danger"></i> These files appear in the database, but the actual files are missing from disk.</p>
    <div class="table-responsive">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th>File</th>
                <th>Size</th>
                <th>Last Modified</th>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $file)
                <tr>
                    <td>
                        <a href="{{route('projects.files.show', [$file->project_id, $file])}}"
                           class="no-underline">
                            {{ $file->getFilePath()}}
                        </a>
                    </td>
                    <td>{{number_format($file->size) . ' bytes'}}</td>
                    <td>
                        <small>{{Carbon::parse($file->created_at)->diffForHumans()}}</small>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
