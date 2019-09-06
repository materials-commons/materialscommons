@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop

@section('content')
    @component('components.card')
        @slot('header')
            Public Datasets
        @endslot

        @slot('body')
            <table id="datasets" class="table" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Authors</th>
                </tr>
                </thead>
            </table>
        @endslot
    @endcomponent

    {{--    @component('components.card')--}}
    {{--        @slot('header')--}}
    {{--            Public Projects--}}
    {{--        @endslot--}}

    {{--        @slot('body')--}}
    {{--        @endslot--}}
    {{--    @endcomponent--}}

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
                        {name: 'description'},
                        {name: 'authors'},
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
