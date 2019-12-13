<form>
    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" value="{{$user->name}}" readonly>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" readonly>{{$user->description}}</textarea>
    </div>
</form>