<table id="attributes" class="table table-hover" style="width:100%">
    <thead>
    <tr>
        <th>Attribute</th>
        <th>Description</th>
        <th>Updated</th>
        <th>Value</th>
        <th>Unit</th>
    </tr>
    </thead>
    <tbody>
    @foreach($attributes as $attribute)
        @if ($attribute->values->count() !== 0)
            @foreach($attribute->values as $value)
                @if ($loop->first)
                    <tr>
                        <td>
                            {{$attribute->name}}
                        </td>
                        <td>{{$attribute->description}}</td>
                        <td>{{$attribute->updated_at->diffForHumans()}}</td>
                        <td>@json($value->val["value"])</td>
                        <td>{{$value->unit}}</td>
                    </tr>
                @else
                    <tr>
                        <td>
                            <span class="ml-3">{{$attribute->name}}</span>
                        </td>
                        <td><span hidden>{{$attribute->description}}</span></td>
                        <td><span hidden>{{$attribute->updated_at->diffForHumans()}}</span></td>
                        <td>@json($value->val["value"])</td>
                        <td>{{$value->unit}}</td>
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td>
                    {{$attribute->name}}/{{$attribute->id}}
                </td>
                <td>{{$attribute->description}}</td>
                <td>{{$attribute->updated_at->diffForHumans()}}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#attributes').DataTable({
                stateSave: true,
            });
        });
    </script>
@endpush