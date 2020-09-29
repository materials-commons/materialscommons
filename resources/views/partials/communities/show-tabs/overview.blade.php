<form>
    <div class="form-group">
        <label for="name">Name</label>
        <p>{{$community->name}}
    </div>

    <x-show-description :description="$community->description"/>
</form>