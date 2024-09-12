<div>
    {{--    <div class="row">--}}
    {{--        <div class="col-12">--}}
    {{--            <a class="action-link float-right">--}}
    {{--                <i class="fa fas fa-plus mr-2"></i> Add Filtered View--}}
    {{--            </a>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="row">--}}
    <ul class="nav nav-tabs col-12">
        <li class="nav-item">
            <a class="nav-link no-underline active"
               href="{{route('projects.datahq.index', [$project, 'tab' => 'samples'])}}">
                All Samples
            </a>
        </li>
    </ul>
    {{--    </div>--}}
    <br/>
    <div class="row">
        <div class="form-group">
            <label class="ml-4">View Data As:</label>
            <select name="what" class="selectpicker" title="Type of View (Table/Chart)" id="view-as"
                    data-style="btn-light no-tt">
                <option value="table">Table</option>
                <option value="line-chart">Line Chart</option>
                <option value="bar-chart">Bar Chart</option>
                <option value="scatter-chart">Scatter Chart</option>
            </select>
        </div>
    </div>

    <x-projects.samples.samples-table :project="$project"/>
</div>