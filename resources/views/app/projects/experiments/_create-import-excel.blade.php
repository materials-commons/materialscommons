<div class="row mr-2 ml-1">
    <div class="col-md-12 white-box">
        <h5 class="text-centerx mt-3 mr-2 font-weight-bold">
            <i class="fas fa-file-excel me-2"></i> Excel File
        </h5>
        <hr/>
        <div class="mb-3">
            <label for="file_id">Select spreadsheet</label>
            <select name="file_id" class="selectpicker w-100"
                    data-live-search="true"
                    data-width="100%"
                    data-style="btn-light no-tt"
                    title="Select Spreadsheet">
                <option value=""></option>
                @foreach($excelFiles as $f)
                    <option data-tokens="{{$f->id}}"
                            value="{{$f->id}}">
                        {{$f->directory->path === "/" ? "" : $f->directory->path}}
                        /{{$f->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
