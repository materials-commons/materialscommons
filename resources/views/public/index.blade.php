@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    <br>
    <h4>
        Welcome to Materials Commons published datasets. Here you will find data published by the Materials Science
        community.
        We provide many ways to explore and download the data. You can also easily publish your own data.
    </h4>
    <br>
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
            $(document).ready(() => {
                $('#datasets').DataTable({
                    serverSide: true,
                    processing: true,
                    response: true,
                    stateSave: true,
                    ajax: "{{route('get_all_published_datasets')}}",
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
                                return data.substr(0, space);
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
