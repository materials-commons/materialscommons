@if(isInBeta('dashboard-charts'))
    {{-- ══ File KPI strip ═══════════════════════════════════════════════════════════ --}}
    <div class="row g-2 mb-3">
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Size</div>
                <div class="fw-bold text-primary" style="font-size:.95rem;">{{ $file->toHumanBytes() }}</div>
                <div class="text-muted" style="font-size:.65rem;">on disk</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Type</div>
                <div class="fw-bold text-secondary" style="font-size:.85rem; word-break:break-word;">
                    {{ $file->mimeTypeToDescriptionForDisplay($file) }}
                </div>
                <div class="text-muted" style="font-size:.65rem;">format</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Samples</div>
                <div class="fw-bold fs-5 text-primary">{{ number_format($file->entities_count) }}</div>
                <div class="text-muted" style="font-size:.65rem;">linked</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Processes</div>
                <div class="fw-bold fs-5 text-info">{{ number_format($file->activities_count) }}</div>
                <div class="text-muted" style="font-size:.65rem;">linked</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Versions</div>
                <div class="fw-bold fs-5 text-secondary">{{ $previousVersions->count() }}</div>
                <div class="text-muted" style="font-size:.65rem;">total</div>
            </div>
        </div>
        <div class="col-6 col-sm-2">
            <div class="card border-0 shadow-sm h-100 text-center py-2">
                <div class="text-muted small">Uploaded</div>
                <div class="fw-bold text-muted" style="font-size:.75rem;">
                    {{ $file->created_at->format('M j, Y') }}
                </div>
                <div class="text-muted" style="font-size:.65rem;">{{ $file->created_at->diffForHumans() }}</div>
            </div>
        </div>
    </div>
@endif
@isset($project)
    {{--                <a class="float-end action-link" href="#">--}}
    {{--                    <i class="fas fa-edit me-2"></i>Edit--}}
    {{--                </a>--}}

    {{--                <a class="float-end action-link me-4" href="#">--}}
    {{--                    <i class="fas fa-trash-alt me-2"></i>Delete--}}
    {{--                </a>--}}

    @if ($file->mime_type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
        <a class="float-end action-link me-4"
           href="{{route('projects.files.create-experiment', [$project, $file])}}">
            <i class="fas fa-file-import me-2"></i>Create Study From Spreadsheet
        </a>
    @endif
    <a class="action-link float-end me-4"
       href="{{route('projects.files.download', [$project, $file])}}">
        <i class="fas fa-download me-2"></i>Download File
    </a>

    <a class="action-link float-end me-4" href="{{route('projects.files.delete', [$project, $file])}}">
        <i class="fas fa-fw fa-trash me-2"></i>Delete
    </a>
@endisset
<br/>
@include('partials.files._file-header-controls', [
    'displayRoute' => route('projects.files.display', [$project, $file]),
    'editRoute' => route('projects.files.edit', [$project, $file]),
])

<hr>
<br>

@include('partials.files._display-file', [ 'displayRoute' => route('projects.files.display', [$project, $file]) ])

