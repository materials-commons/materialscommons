<form>
    {{--    <div class="form-group">--}}
    {{--        <label for="description">Description</label>--}}
    {{--        <textarea class="form-control" id="description" name="description" type="text"--}}
    {{--                  placeholder="Description..." readonly>{{$item->description}}</textarea>--}}
    {{--    </div>--}}
    <div class="form-group">
        <label for="description">Description</label>
        <div class="markdown-area mb-2" id="description">
            @markdown($item->description)
        </div>
    </div>
    <div class="form-row">
        <div class="col h6">
            <span>Owner: {{$item->owner->name}}</span>
            <span class="ml-4">Last Updated {{$item->updated_at->diffForHumans()}}</span>
            {{$slot}}
        </div>
    </div>
</form>

{{--<table class="table table-bordered">--}}
{{--    <thead>--}}
{{--    <th>Name</th>--}}
{{--    </thead>--}}
{{--    <tbody>--}}
{{--    <tr>--}}
{{--        <td>Bob</td>--}}
{{--    </tr>--}}
{{--    <tbody>--}}
{{--</table>--}}