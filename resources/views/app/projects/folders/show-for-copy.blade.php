@extends('layouts.app')

@section('pageTitle', 'Files')

@section('content')
    <h4>
        Select Destination For Copy Of Directory '{{$fromDirectory->path}}' From Project
        '{{$fromDirectory->project->name}}'.
        You Are Currently Selecting Destination In Project '{{$project->name}}'.
    </h4>
    <x-card>
        <x-slot name="header">
            <x-show-dir-path :project="$project" :file="$directory"/>

            <a class="float-right action-link mr-4"
               href="{{route('projects.folders.copy-to', [$project, $fromDirectory, $project->rootDir, $copyType])}}">
                <i class="fas fa-check mr-2"></i>Copy Here
            </a>
        </x-slot>

        <x-slot name="body">
            @if ($directory->path !== '/')
                <a href="{{route('projects.folders.show-for-copy', [$fromProject, $fromDirectory, $directory->directory_id, $copyType])}}"
                   class="mb-3">
                    <i class="fa-fw fas fa-arrow-alt-circle-up mr-2"></i>Go up one level
                </a>
                <br>
                <br>
            @endif

            <div class="row">
                <div class="col-5">
                    <table id="left" class="table table-hover" stylex="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Real Size</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fromFiles as $file)
                            <tr draggable="true">
                                <td>
                                    <a class="no-underline"
                                       href="{{route('projects.folders.show-for-copy', [$fromProject, $fromDirectory, $file, $copyType])}}">
                                        <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                    </a>
                                </td>
                                <td>{{$file->mime_type}}</td>
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

                <div class="col-5">
                    <table id="right" class="table table-hover" stylex="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Real Size</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file)
                            @if($file->isDir())
                                <tr draggable="true">
                                    <td>
                                        <a class="no-underline"
                                           href="{{route('projects.folders.show-for-copy', [$project, $fromDirectory, $file, $copyType])}}">
                                            <i class="fa-fw fas fa-folder mr-2"></i> {{$file->name}}
                                        </a>
                                    </td>
                                    <td>{{$file->mime_type}}</td>
                                    @if($file->isDir())
                                        <td></td>
                                    @else
                                        <td>{{$file->toHumanBytes()}}</td>
                                    @endif
                                    <td>{{$file->size}}</td>
                                </tr>
                            @endif
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