@props(['experiments'])
<div class="input-group">
    <div class="input-group-prepend">
        <label class="input-group-text" for="select-view">View</label>
    </div>
    <select class="custom-select" id="select-view">
        <option selected>Project</option>
        @foreach($experiments as $experiment)
            <option>Study: {{$experiment->name}}</option>
        @endforeach
    </select>
</div>