@extends('layouts.app')

@section('pageTitle', 'Files')

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            Copy Files/Directories
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-5">
                    <h4>Project: {{$leftProject->name}}</h4>
                    <h5>Path: {{$leftDirectory->path}}</h5>
                    @if ($leftDirectory->path !== '/')
                        <a href="{{route('projects.folders.show-for-copy', [$leftProject, $leftDirectory->directory_id, $rightProject, $rightDirectory])}}"
                           class="mb-3">
                            <i class="fa-fw fas fa-arrow-alt-circle-up mr-2"></i>Go up one level
                        </a>
                        <br>
                        <br>
                    @endif
                    <table id="left" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Real Size</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leftFiles as $file)
                            <tr draggable="true">
                                <td>
                                    @if($file->isDir())
                                        <a class="no-underline"
                                           href="{{route('projects.folders.show-for-copy', [$leftProject, $file, $rightProject, $rightDirectory])}}">
                                            <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                        </a>
                                    @else
                                        <a class="no-underline"
                                           href="{{route('projects.files.show', [$leftProject, $file])}}">
                                            <i class="fa-fw fas fa-file mr-2"></i> {{$file->name}}
                                        </a>
                                    @endif
                                </td>
                                <td>{{$file->mimeTypeToDescriptionForDisplay($file)}}</td>
                                @if($file->isDir())
                                    <td></td>
                                @else
                                    <td>{{$file->toHumanBytes()}}</td>
                                @endif
                                <td>{{$file->size}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-1"></div>

                <div class="col-5">
                    <h4>Project: {{$rightProject->name}}</h4>
                    <h5>Path: {{$rightDirectory->path}}</h5>
                    @if ($rightDirectory->path !== '/')
                        <a href="{{route('projects.folders.show-for-copy', [$leftProject, $leftDirectory, $rightProject, $rightDirectory->directory_id])}}"
                           class="mb-3">
                            <i class="fa-fw fas fa-arrow-alt-circle-up mr-2"></i>Go up one level
                        </a>
                        <br>
                        <br>
                    @endif

                    <table id="right" class="table table-hover mt-3" stylex="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Real Size</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rightFiles as $file)
                            <tr draggable="true">
                                <td>
                                    @if($file->isDir())
                                        <a class="no-underline"
                                           href="{{route('projects.folders.show-for-copy', [$leftProject, $leftDirectory, $rightProject, $file])}}">
                                            <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                        </a>
                                    @else
                                        <a class="no-underline"
                                           href="{{route('projects.files.show', [$rightProject, $file])}}">
                                            <i class="fa-fw fas fa-file mr-2"></i> {{$file->name}}
                                        </a>
                                    @endif
                                </td>
                                <td>{{$file->mimeTypeToDescriptionForDisplay($file)}}</td>
                                @if($file->isDir())
                                    <td></td>
                                @else
                                    <td>{{$file->toHumanBytes()}}</td>
                                @endif
                                <td>{{$file->size}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
    </x-card>

    @push('scripts')
        <script>
            $(document).ready(() => {
                let dragSrcRow = null;  // Keep track of the source row
                let selectedRows = null;   // Keep track of selected rows in the source table
                let srcTable = '';  // Global tracking of table being dragged for 'over' class setting
                let rows, rows2;

                $('#left').DataTable({
                    columnDefs: [
                        {orderData: [3], targets: [2]},
                        {targets: [3], visible: false},
                    ],
                    drawCallback: function () {
                        rows = document.querySelectorAll("#left tbody tr");
                        [].forEach.call(rows, function (row) {
                            row.addEventListener('dragstart', handleDragStart, false);
                            row.addEventListener('dragenter', handleDragEnter, false)
                            row.addEventListener('dragover', handleDragOver, false);
                            row.addEventListener('dragleave', handleDragLeave, false);
                            row.addEventListener('drop', handleDrop, false);
                            row.addEventListener('dragend', handleDragEnd, false);
                        });
                    }
                });

                $('#right').DataTable({
                    columnDefs: [
                        {orderData: [3], targets: [2]},
                        {targets: [3], visible: false},
                    ],
                    drawCallback: function () {
                        rows2 = document.querySelectorAll("#right tbody tr");
                        [].forEach.call(rows2, function (row) {
                            row.addEventListener('dragstart', handleDragStart, false);
                            row.addEventListener('dragenter', handleDragEnter, false)
                            row.addEventListener('dragover', handleDragOver, false);
                            row.addEventListener('dragleave', handleDragLeave, false);
                            row.addEventListener('drop', handleDrop, false);
                            row.addEventListener('dragend', handleDragEnd, false);
                        });
                    }
                });

                function handleDragStart(e) {
                    console.log('handleDragStart');
                    // this / e.target is the source node.

                    // Set the source row opacity
                    this.style.opacity = '0.4';

                    // Keep track globally of the source row and source table id
                    dragSrcRow = this;
                    srcTable = this.parentNode.parentNode.id;

                    // Keep track globally of selected rows
                    selectedRows = $('#' + srcTable).DataTable().rows({selected: true});

                    // Allow moves
                    e.dataTransfer.effectAllowed = 'move';

                    // Save the source row html as text
                    e.dataTransfer.setData('text/plain', e.target.outerHTML);
                }

                function handleDragOver(e) {
                    if (e.preventDefault) {
                        e.preventDefault(); // Necessary. Allows us to drop.
                    }

                    // Allow moves
                    e.dataTransfer.dropEffect = 'move';

                    return false;
                }

                function handleDragEnter(e) {
                    // this / e.target is the current hover target.

                    // Get current table id
                    var currentTable = this.parentNode.parentNode.id

                    // Don't show drop zone if in source table
                    if (currentTable !== srcTable) {
                        this.classList.add('over');
                    }
                }

                function handleDragLeave(e) {
                    // this / e.target is previous target element.

                    // Remove the drop zone when leaving element
                    this.classList.remove('over');
                }

                function handleDrop(e) {
                    // this / e.target is current target element.

                    console.log('handleDrop = ', e);

                    if (e.stopPropagation) {
                        e.stopPropagation(); // stops the browser from redirecting.
                    }

                    // Get destination table id, row
                    var dstTable = $(this.closest('table')).attr('id');

                    // No need to process if src and dst table are the same
                    if (srcTable === dstTable) {
                        return false;
                    }

                    // If selected rows and dragged item is selected then move selected rows
                    if (selectedRows.count() > 0 && $(dragSrcRow).hasClass('selected')) {

                        // Add row to destination Datatable
                        $('#' + dstTable).DataTable().rows.add(selectedRows.data()).draw();

                        // Remove row from source Datatable
                        $('#' + srcTable).DataTable().rows(selectedRows.indexes()).remove().draw();

                    } else {  // Otherwise move dragged row
                        // console.log("  in else");

                        // Get source transfer data
                        var srcData = e.dataTransfer.getData('text/plain');
                        console.log(e.dataTransfer);

                        console.log("   srcData = ", srcData);
                        let href = $(srcData).attr('href');
                        console.log('href = ' + href);
                        fetch(href);
                        // Add row to destination Datatable
                        // $('#' + dstTable).DataTable().row.add($(srcData)).draw();
                        //
                        // console.log("   past add");
                        //
                        // // Remove row from source Datatable
                        // $('#' + srcTable).DataTable().row(dragSrcRow).remove().draw();

                    }

                    return false;
                }

                function handleDragEnd(e) {
                    // this/e.target is the source node.

                    // Reset the opacity of the source row
                    this.style.opacity = '1.0';

                    // Clear 'over' class from both tables
                    // and reset opacity
                    [].forEach.call(rows, function (row) {
                        row.classList.remove('over');
                        row.style.opacity = '1.0';
                    });

                    [].forEach.call(rows2, function (row) {
                        row.classList.remove('over');
                        row.style.opacity = '1.0';
                    });
                }

            });
        </script>
    @endpush

@stop