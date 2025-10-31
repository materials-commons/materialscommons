<span class="fs-14"><b>OR</b></span>
<br/>
<br/>
<label for="sheet_id">Select existing Google Spreadsheet</label>
<select name="sheet_id" class="form-select" title="Select Google Sheet">
    <option value="">Select Google Sheet</option>
    @foreach($sheets as $s)
        <option data-tokens="{{$s->id}}" value="{{$s->id}}">{{$s->title}}</option>
    @endforeach
</select>
