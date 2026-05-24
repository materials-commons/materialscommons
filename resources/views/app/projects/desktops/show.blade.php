@extends('layouts.app')

@section('pageTitle', "{$project->name} - View Project")

@section('nav')
    @include('layouts.navs.app.project')
@stop

{{--@section('breadcrumbs', Breadcrumbs::render('projects.show', $project))--}}

@section('content')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <div class="text-muted text-uppercase fw-semibold mb-1" style="font-size:.75rem; letter-spacing:.04em;">
                        Desktop Browser
                    </div>
                    <h5 class="mb-2">
                        <i class="fas fa-desktop me-1 text-muted"></i>
                        {{ $hostname }}
                    </h5>
                    <div class="d-flex flex-wrap gap-2 text-muted small">
                        <span>
                            <span class="fw-semibold">Desktop ID:</span>
                            {{ $desktopId }}
                        </span>
                        <span class="d-none d-sm-inline">&middot;</span>
                        <span>
                            <span class="fw-semibold">Directory:</span>
                            <code class="bg-light border rounded px-2 py-1 text-dark">{{ $dir }}</code>
                        </span>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-start justify-content-lg-end gap-2">
                    <a href="{{ route('projects.desktops.submit-test-upload', [$project, $desktopId]) }}"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-upload me-1"></i>
                        Upload All
                    </a>

                    <a href="{{ route('projects.desktops.submit-test-upload', [$project, $desktopId]) }}"
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i>
                        Download All
                    </a>

                    <a href="{{ route('projects.desktops.submit-test-upload', [$project, $desktopId]) }}"
                       class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sync-alt me-1"></i>
                        Synchronize
                    </a>

                    <a href="{{ route('projects.desktops.submit-test-upload', [$project, $desktopId]) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-folder-open me-1"></i>
                        Find Files
                    </a>

                    <a href="{{ route('projects.desktops.submit-test-upload', [$project, $desktopId]) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-search me-1"></i>
                        Search Files
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <div>
                <h6 class="mb-0">
                    <i class="fas fa-folder-tree me-1 text-muted"></i>
                    Desktop Files
                </h6>
                <div class="text-muted small">
                    Files and directories detected for this desktop.
                </div>
            </div>
        </div>

        <div class="card-body background-white mt-3 p-3">
            <div class="table-responsive">
                <table id="desktop-client" class="table table-hover table-sm align-middle mb-0" style="width: 100%">
                    <thead class="table-light">
                    <tr>
                        <th>File</th>
                        <th>Client Type</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Action</th>
                        <th>Reason</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clientFiles as $file)
                        <tr>
                            <td class="fw-semibold">
                                @if($file->r_type == "D")
                                    @php
                                        if ($dir == "/") {
                                            $fileDir = "{$dir}{$file->name}";
                                        } else {
                                            $fileDir = "{$dir}/{$file->name}";
                                        }
                                    @endphp
                                    <a href="{{ route('projects.desktops.show', [$project, $desktopId, $hostname, 'dir' => "{$fileDir}"]) }}"
                                       class="text-decoration-none">
                                        <i class="fas fa-folder me-1 text-warning"></i>
                                        {{ $file->name }}
                                    </a>
                                @else
                                    <i class="fas fa-file me-1 text-muted"></i>
                                    {{ $file->name }}
                                @endif
                            </td>
                            <td>
                                <span class="badge text-bg-light border">{{ $file->l_type ?: '—' }}</span>
                            </td>
                            <td>
                                <span class="badge text-bg-light border">{{ $file->r_type ?: '—' }}</span>
                            </td>
                            <td>{{ $file->local_remote }}</td>
                            <td>
                                <span class="badge text-bg-primary">{{ $file->action }}</span>
                            </td>
                            <td class="text-muted">{{ $file->reason }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#desktop-client').DataTable({
                    pageLength: 100,
                });
            });
        </script>
    @endpush
@stop
