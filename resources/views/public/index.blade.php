@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    <h4 class="text-muted mb-3" style="font-size:1rem;">Welcome to Materials Commons published datasets.</h4>

    {{-- ══ Browse-by discovery strip ══════════════════════════════════════════════ --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <a href="{{ route('public.communities.index') }}" class="text-decoration-none">
                <div class="card border-0 h-100 shadow-sm browse-card" style="transition:box-shadow .15s;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10"
                             style="width:52px; height:52px;">
                            <i class="fas fa-layer-group fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 text-primary lh-1">{{ number_format($communities->count()) }}</div>
                            <div class="fw-semibold" style="font-size:.9rem;">Communities</div>
                            <div class="text-muted" style="font-size:.75rem;">Curated dataset collections</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('public.authors.index') }}" class="text-decoration-none">
                <div class="card border-0 h-100 shadow-sm browse-card" style="transition:box-shadow .15s;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10"
                             style="width:52px; height:52px;">
                            <i class="fas fa-users fa-lg text-success"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 text-success lh-1">
                                <i class="fas fa-arrow-right me-1" style="font-size:.8rem;"></i>Browse
                            </div>
                            <div class="fw-semibold" style="font-size:.9rem;">By Author</div>
                            <div class="text-muted" style="font-size:.75rem;">Profiles, datasets &amp; analytics</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="{{ route('public.tags.index') }}" class="text-decoration-none">
                <div class="card border-0 h-100 shadow-sm browse-card" style="transition:box-shadow .15s;">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10"
                             style="width:52px; height:52px;">
                            <i class="fas fa-tags fa-lg text-warning"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 text-warning lh-1">{{ number_format($tagCount) }}</div>
                            <div class="fw-semibold" style="font-size:.9rem;">Browse by Tag</div>
                            <div class="text-muted" style="font-size:.75rem;">{{ $tagCount }} research topics</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- ══ Special collections ══════════════════════════════════════════════════════ --}}
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="text-muted fw-semibold text-uppercase"
              style="font-size:.7rem; letter-spacing:.06em; white-space:nowrap;">Special Collections</span>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <a href="{{ route('public.openvisus.index', ['tag' => 'OpenVisus']) }}"
               class="d-flex align-items-center gap-2 text-decoration-none border rounded px-2 py-1 bg-light browse-card"
               style="transition:box-shadow .15s;">
                <img src="https://avatars.githubusercontent.com/u/1258106?s=400&v=4"
                     width="24" height="24" class="rounded-circle" alt="OpenVisus">
                <span class="fw-semibold text-dark" style="font-size:.85rem;">OpenVisus</span>
            </a>
            <a href="/uhcsdb"
               class="d-flex align-items-center gap-2 text-decoration-none border rounded px-2 py-1 bg-light browse-card"
               style="transition:box-shadow .15s;">
                <i class="fas fa-flask text-secondary" style="font-size:.85rem;"></i>
                <span class="fw-semibold text-dark" style="font-size:.85rem;">Ultrahigh Carbon Steel (UHCSDB)</span>
            </a>
        </div>
    </div>

    <h2 class="text-center">Public Datasets</h2>
    <hr class="mb-3"/>

    <x-public.datasets-analytics :is-test="$isTest"/>

    <table id="datasets" class="table table-hover" style="width:100%">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Views</th>
            <th>Downloads</th>
            <th>Published</th>
            <th>Summary</th>
            <th>Authors</th>
        </tr>
        </thead>
    </table>

    @push('styles')
        <style>
            .browse-card:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important; }
        </style>
    @endpush

    @push('scripts')
        <script>
            @php
                if ($isTest) {
                    $r = route('get_all_published_test_datasets');
                } else {
                    $r = route('get_all_published_datasets');
                }
            @endphp
            $(document).ready(() => {
                $('#datasets').DataTable({
                    pageLength: 100,
                    serverSide: true,
                    processing: true,
                    response: true,
                    stateSave: true,
                    ajax: "{{$r}}",
                    order: [[4, "desc"]],
                    columns: [
                        {
                            name: 'name',
                            render: function (data, type, row) {
                                let r = route('public.datasets.show', row["1"]);
                                if (type === 'display') {
                                    let ndata = `<a href="` + r + `" class="no-underline">` + data + '</a>';
                                    return ndata;
                                }

                                return data;
                            }
                        },
                        {name: 'id'},
                        {name: 'views_count', searchable: false},
                        {name: 'downloads_count', searchable: false},
                        {
                            @if($isTest)
                            name: 'test_published_at',
                            @else
                            name: 'published_at',
                            @endif
                            render: function (data, type, row) {
                                let space = data.indexOf(' ');
                                return data.slice(0, space);
                            }
                        },
                        {name: 'summary'},
                        {
                            name: 'ds_authors',
                            render: function (data) {
                                if (!data) {
                                    return "";
                                }
                                return data.map(function (author) {
                                    return author['name'];
                                }).join(', ');
                            }
                        },
                    ],
                    columnDefs: [
                        {
                            targets: [1],
                            visible: false,
                        }
                    ]
                });
            });
        </script>
    @endpush
@stop
