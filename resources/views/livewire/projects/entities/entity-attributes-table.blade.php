<div>
    <x-table.table-search/>
    <table id="entities-dd" class="table table-hover mt-2" style="width:100%">
        <thead class="thead-fixed">
        <tr>
            <th>
                <x-table.col-sortable :column="'name'" :sort-col="$sortCol" :sort-asc="$sortAsc">
                    Attribute
                </x-table.col-sortable>
            </th>
            <th>Units</th>
            <th>Min</th>
            <th>Max</th>
            {{--                <th>Median</th>--}}
            {{--                <th>Avg</th>--}}
            {{--                <th>Mode</th>--}}
            <th># Values</th>
        </tr>
        </thead>
        <tbody>
        @foreach($entityAttributes as $name => $attrs)
            <tr wire:key="{{$name}}">
                <td>
                    <a href="{{$this->entityAttributeRoute($name)}}">{{$name}}</a>
                </td>
                <td>{{$this->units($attrs)}}</td>
                <td>{{$this->min($attrs)}}</td>
                <td>{{$this->max($attrs)}}</td>
                {{--                    <td>{{$median($attrs)}}</td>--}}
                {{--                    <td>{{$average($attrs)}}</td>--}}
                {{--                    <td>{{$mode($attrs)}}</td>--}}
                <td>{{$attrs->count()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>
        {{$entityAttributes->links()}}
    </div>
</div>
