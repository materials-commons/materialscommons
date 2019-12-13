<form>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" type="text"
                  placeholder="Description..." readonly>{{$item->description}}</textarea>
    </div>
    <div class="form-row">
        <div class="col h5">
            <span>Owner: {{$item->owner->name}}</span>
            <span class="ml-4">Last Updated {{$item->updated_at->diffForHumans()}}</span>
        </div>
    </div>
</form>