<form method="post" action="{{$importDatasetRoute}}" id="import-dataset">
    @csrf

    <p>
        Importing a dataset will import all the datasets files into a directory in a project. The directory will
        be created by the import process and must not exist in the project.
    </p>

    <div class="form-group">
        <label for="project">Project</label>
        <input class="form-control" id="name" value="{{$project->name}}" readonly>
    </div>
    <div class="form-group">
        <label for="directory">Directory</label>
        <input class="form-control" id="directory" name="directory" placeholder="Directory..."
               value="{{old('directory', $dataset->importDirectory())}}">
    </div>
    <div class="float-end">
        <a href="{{$cancelImportRoute}}" class="action-link danger me-3">
            Cancel
        </a>

        <a class="action-link" href="#" onclick="document.getElementById('import-dataset').submit()">
            Import
        </a>
    </div>

    @include('common.errors')
</form>