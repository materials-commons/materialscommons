@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    <h4>
        Welcome to Materials Commons published datasets.
        See our other special collections of published data: <a
            href="{{route('public.openvisus.index', ['tag' => 'OpenVisus'])}}">
            <img src="https://avatars.githubusercontent.com/u/1258106?s=400&v=4" width="100px"/></a>,
        <a href="/uhcsdb">Ultrahigh Carbon Steel (UHCSDB)</a>
    </h4>

    <h2 class="text-center">Public Datasets</h2>
    <hr class="mb-5"/>
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
