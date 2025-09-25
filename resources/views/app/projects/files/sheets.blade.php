@extends('layouts.app')

@section('pageTitle', "{$project->name} - Sheets")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">Sheets</x-slot>
        <x-slot name="body">
            <x-table-container>
                <div class="float-right">
                    <a data-toggle="modal" href="#add-google-sheet-modal"
                       class="btn btn-success">
                        <i class="fa fas fa-plus mr-2"></i>Add Google Sheet
                    </a>
                </div>
                @include('app.projects.files._new-sheet-modal')
                <br>
                <br>
                <br>
                <table id="sheets" class="table table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Directory</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sheets as $sheet)
                        <tr>
                            @if(isset($sheet->url))
                                <td>
                                    <a href="{{$sheet->url}}" class="no-underline" target="_blank">{{$sheet->title}}</a>
                                </td>
                                <td>Google Sheet</td>
                                <td></td>
                            @else
                                <td>
                                    <a href="{{route('projects.files.show', [$sheet->project_id, $sheet->id])}}"
                                       class="no-underline">{{App\Helpers\PathHelpers::joinPaths($sheet->directory->path, $sheet->name)}}</a>
                                </td>
                                <td>
                                    @if($sheet->mime_type === "text/csv")
                                        CSV
                                    @else
                                        Excel
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('projects.folders.show', [$sheet->project_id, $sheet->directory->id])}}"
                                       class="no-underline">{{$sheet->directory->path}}</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-table-container>
        </x-slot>
    </x-card>

    @push('scripts')
        <script>
            document.addEventListener('livewire:navigating', () => {
                $('#sheets').DataTable().destroy();
            }, {once: true});

            $(document).ready(() => {
                $('#sheets').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
@stop
