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
    @component('components.card')
        @slot('header')
            Public Datasets
            @include('public._add-data-and-publish')
        @endslot

        @slot('body')
            <table id="datasets" class="table table-hover" style="width:100%">
                <thead>
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
        @endslot
    @endcomponent

    @push('scripts')
        <script>
            @php
                $isTest = request()->input('test');
                if (is_null($isTest)) {
                    $r = route('get_all_published_datasets');
                } else {
                    $r = route('get_all_published_test_datasets');
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
                                    let ndata = `<a href="` + r + `">` + data + '</a>';
                                    return ndata;
                                }

                                return data;
                            }
                        },
                        {name: 'id'},
                        {name: 'views_count', searchable: false},
                        {name: 'downloads_count', searchable: false},
                        {
                            name: 'published_at',
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
