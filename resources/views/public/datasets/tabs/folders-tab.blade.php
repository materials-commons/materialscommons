@include('public.datasets.tabs._short-overview')

{{--@section('content')--}}
    <x-card>
        <x-slot name="header">
            @if ($directory->name == '/')
                {{$directory->name}}
            @else
                @if(sizeof($dirPaths) == 1)
                    {{$directory->name}}
                @else
                    @foreach($dirPaths as $dirpath)
                        <a class="action-link"
                           href="{{route('public.datasets.folders.by-path', ['dataset' => $dataset, 'path' => $dirpath["path"]])}}">
                            {{$dirpath['name']}}/
                        </a>
                    @endforeach
                @endif
            @endif
        </x-slot>

        <x-slot name="body">
            @if ($directory->path !== '/')
                <a href="{{route('public.datasets.folders.show', [$dataset, $directory->directory_id])}}" class="mb-3">
                    <i class="fa-fw fas fa-arrow-alt-circle-up mr-2"></i>Go up one level
                </a>
                <br>
                <br>
            @endif

            <table id="files" class="table table-hover" style="width:100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Real Size</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>
                            @if($file->isDir())
                                <a class="no-underline" href="{{route('public.datasets.folders.show', [$dataset, $file])}}">
                                    <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                </a>
                            @else
                                <a class="no-underline" href="{{route('public.datasets.files.show', [$dataset, $file])}}">
                                    <i class="fa-fw fas fa-file mr-2"></i> {{$file->name}}
                                </a>
                            @endif
                        </td>
                        <td>{{$file->mime_type}}</td>
                        @if($file->isDir())
                            <td></td>
                        @else
                            <td>{{$file->toHumanBytes()}}</td>
                        @endif
                        <td>{{$file->size}}</td>
                        <td>
                            @if($file->isImage())
                                {{-- Change next two routes to public.datasets.files.display once that is implemented --}}
                                <a href="{{route('public.datasets.files.show', [$dataset, $file])}}">

                                    <img src="{{route('public.datasets.files.display', [$dataset, $file])}}"
                                         style="width: 12rem">
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </x-slot>
    </x-card>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#files').DataTable({
                    stateSave: true,
                    columnDefs: [
                        {orderData: [3], targets: [2]},
                        {targets: [3], visible: false},
                    ]
                });
            });
        </script>
    @endpush

{{--@stop--}}
