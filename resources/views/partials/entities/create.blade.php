<form method="post" action="{{$storeEntityRoute}}" id="create-entity">
    @csrf

    <div class="mb-3">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
               placeholder="Name...">
    </div>

    <div class="mb-3">
        <label for="summary">Summary</label>
        <input class="form-control" id="summary" name="summary" type="text" value="{{old('summary')}}"
               placeholder="Summary...">
    </div>

    <div class="mb-3">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" type="text"
                  placeholder="Description...">{{old('description')}}</textarea>
    </div>

    @isset($experiments)
        <div class="mb-3">
            <label for="experiments">Studies</label>
            <select name="experiment_id" class="form-select" title="experiments">
                @foreach($experiments as $experiment)
                    <option value="">Select a study</option>
                    <option data-token="{{$experiment->id}}" value="{{$experiment->id}}">
                        {{$experiment->name}}
                    </option>
                @endforeach
            </select>
        </div>
    @endisset

    <div class="float-end">
        <a href="{{$cancelRoute}}" class="action-link danger me-3">
            Cancel
        </a>

        <a class="action-link" href="#" onclick="document.getElementById('create-entity').submit()">
            Create
        </a>
    </div>
</form>
<br>
<br>
@include('common.errors')
