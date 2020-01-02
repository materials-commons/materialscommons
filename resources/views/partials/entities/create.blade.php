<form method="post" action="{{$storeEntityRoute}}" id="create-entity">
    @csrf

    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="" placeholder="Name...">
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" type="text"
                  placeholder="Description..."></textarea>
    </div>

    @isset($experiments)
        <div class="form-group">
            <label for="experiments">Experiments</label>
            <select name="experiment_id" class="selectpicker col-lg-8"
                    title="experiments"
                    data-live-search="true">
                @foreach($experiments as $experiment)
                    <option data-token="{{$experiment->id}}" value="{{$experiment->id}}">
                        {{$experiment->name}}
                    </option>
                @endforeach
            </select>
        </div>
    @endisset

    <div class="float-right">
        <a href="{{$cancelRoute}}" class="action-link danger mr-3">
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