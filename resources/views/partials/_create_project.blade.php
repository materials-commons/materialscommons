<form method="post" action="{{$createProjectRoute}}" id="project-create">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="" placeholder="Name...">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" type="text" value=""
                  placeholder="Description..."></textarea>
    </div>
    <div class="float-right">
        <a href="{{$cancelRoute}}" class="action-link danger mr-3">
            Cancel
        </a>

        <a class="action-link" href="#" onclick="document.getElementById('project-create').submit()">
            Create
        </a>
    </div>
</form>