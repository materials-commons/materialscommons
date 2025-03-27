<form method="post" action="{{$storeDirectoryRoute}}" id="folder-create">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
               placeholder="Folder...">
    </div>

    <input hidden id="project_id" name="project_id" value="{{$project->id}}">
    <input hidden id="directory_id" name="directory_id" value="{{$directory->id}}">

    <div class="float-right">
        <a class="action-link danger mr-3"
           href="{{$cancelRoute}}">
            Cancel
        </a>

        <a class="action-link" href="#" onclick="document.getElementById('folder-create').submit()">
            Create
        </a>
    </div>
</form>