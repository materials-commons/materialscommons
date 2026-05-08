@props([
    'dataset',
    'userProjects' => collect(),
    'hasNotificationsForDataset' => false,
])

@php
    $authors = collect($dataset->ds_authors ?? []);
    $authorNames = $authors
        ->pluck('name')
        ->filter()
        ->take(3)
        ->join(', ');

    $summary = $dataset->summary ?? $dataset->description ?? null;
@endphp

<div class="card border-0 shadow-sm mb-3" style="border-radius:1rem; overflow:hidden;">
    <div class="card-body p-0 background-white">
        <div class="p-4 p-xl-5"
             style="background:linear-gradient(135deg,#f8fafc 0%,#eef2ff 55%,#eff6ff 100%); border-bottom:1px solid #e5e7eb;">
            <div class="d-flex flex-column flex-xl-row align-items-start justify-content-between gap-4">
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        @if(!blank($dataset->published_at))
                            <span class="badge rounded-pill"
                                  style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0;">
                                <i class="fas fa-check-circle me-1"></i>
                                Published Dataset
                            </span>
                        @else
                            <span class="badge rounded-pill"
                                  style="background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
                                <i class="fas fa-clock me-1"></i>
                                Dataset
                            </span>
                        @endif

                        <span class="badge rounded-pill"
                              style="background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd;">
                            Open Data
                        </span>

                        @if(!blank($dataset->license))
                            <span class="badge rounded-pill"
                                  style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">
                                {{ $dataset->license }}
                            </span>
                        @endif
                    </div>

                    <h1 class="mb-2" style="font-size:1.85rem; line-height:1.2;">
                        {{ $dataset->name }}
                    </h1>

                    @if(!blank($summary))
                        <p class="text-muted mb-3" style="font-size:.98rem; line-height:1.6; max-width:980px;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($summary), 360) }}
                        </p>
                    @endif

                    <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size:.86rem;">
                        @if(!blank($authorNames))
                            <span>
                                <i class="fas fa-user-edit fa-fw me-1"></i>
                                <strong>Authors:</strong>
                                {{ $authorNames }}
                                @if($authors->count() > 3)
                                    <span class="text-muted">+{{ $authors->count() - 3 }} more</span>
                                @endif
                            </span>
                        @endif

                        <span>
                            <i class="far fa-calendar-alt fa-fw me-1"></i>
                            <strong>Published:</strong>
                            {{ $dataset->published_at ? $dataset->published_at->format('M j, Y') : 'Not published' }}
                        </span>

                        @if(!blank($dataset->doi))
                            <span>
                                <i class="fas fa-fingerprint fa-fw me-1"></i>
                                <strong>DOI:</strong>
                                <a href="{{ $dataset->doi }}" class="text-decoration-none">
                                    {{ $dataset->doi }}
                                </a>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 flex-shrink-0" style="min-width:260px; max-width:340px;">
                    <div class="text-muted text-uppercase fw-semibold"
                         style="font-size:.7rem; letter-spacing:.04em;">
                        <i class="fas fa-download me-1"></i>
                        Dataset Access
                    </div>

                    <x-datasets.show-download-links :dataset="$dataset"/>

                    @if(!blank($dataset->doi))
                        <a class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center"
                           data-bs-toggle="modal"
                           href="#cite-dataset-modal">
                            <i class="fas fa-quote-right me-1"></i>
                            Cite Dataset
                        </a>
                    @endif

                    @auth
                        @if(collect($userProjects)->isNotEmpty())
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100 d-flex align-items-center justify-content-center"
                                        type="button"
                                        id="projectsDropdown"
                                        data-bs-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    <i class="fas fa-file-import me-1"></i>
                                    Import to Project
                                </button>
                                <div class="dropdown-menu" aria-labelledby="projectsDropdown">
                                    @foreach($userProjects as $project)
                                        @if($project->owner_id == auth()->id() && $project->id != $dataset->project_id)
                                            <a class="dropdown-item td-none"
                                               href="{{ route('public.datasets.import-into-project', [$dataset, $project]) }}">
                                                {{ $project->name }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($hasNotificationsForDataset)
                            <a class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center"
                               href="#"
                               id="notification"
                               data-bs-toggle="tooltip"
                               title="Stop notifications on dataset"
                               hx-get="{{ route('public.datasets.notifications.unmark-for-notification', [$dataset]) }}"
                               hx-target="#notification"
                               hx-swap="outerHTML">
                                <i class='fa-fw fas fa-bell yellow-4 me-1'></i>
                                Notifications on
                            </a>
                        @else
                            <a class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center"
                               href="#"
                               id="notification"
                               data-bs-toggle="tooltip"
                               title="Get notified when dataset is updated"
                               hx-get="{{ route('public.datasets.notifications.mark-for-notification', [$dataset]) }}"
                               hx-target="#notification"
                               hx-swap="outerHTML">
                                <i class="fas fa-fw fa-bell-slash me-1"></i>
                                Notify me
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
