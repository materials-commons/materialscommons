<label for="file_id">Select Excel file</label>
<select name="file_id" class="form-select"
        title="Select Spreadsheet">
    <option value=""></option>
    @foreach($excelFiles as $f)
        @if ($f->directory->path === "/")
            <option data-tokens="{{$f->id}}" value="{{$f->id}}">/{{$f->name}}</option>
        @else
            <option data-tokens="{{$f->id}}" value="{{$f->id}}">
                {{$f->directory->path}}/{{$f->name}}</option>
        @endif
    @endforeach
</select>
