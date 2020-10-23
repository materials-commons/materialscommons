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

    <hr>

    <p>
        Communities are a way for our members to organize their data. A community is formed around common groups such as
        DFT data or large Syncotron datasets. If you join Materials Commons you can even start your own community.
    </p>
    @component('components.card')
        @slot('header')
            Communities
        @endslot

        @slot('body')
            @include('public.communities._communities_table')
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
                    columns: [
                        {
                            name: 'name',
                            render: function (data, type, row) {
                                let r = route('public.datasets.show', row["1"]).url();
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
                            name: 'authors',
                            render: function (data) {
                                if (!data) {
                                    return "";
                                }
                                return data.split(';').map(function (author) {
                                    let openParen = author.indexOf('(');
                                    if (openParen === -1) {
                                        return author;
                                    }

                                    return author.substr(0, openParen).trim();
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
