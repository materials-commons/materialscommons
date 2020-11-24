<form>
    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" value="{{$user->name}}" readonly>
    </div>
    <div class="form-group">
        <label for="affiliations">Affiliations</label>
        <textarea class="form-control" id="affiliations" readonly>{{$user->affiliations}}</textarea>
    </div>
    @if(isset($user->description))
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" readonly>{{$user->description}}</textarea>
        </div>
    @endif
</form>
{{--@if(isset($datasets)--}}
{{--<br>--}}
{{--<div>--}}
{{--    <h4>Published Datasets</h4>--}}
{{--    <br>--}}
{{--    <table id="datasets" class="table table-hover" style="width:100%">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th>Dataset</th>--}}
{{--            <th>Summary</th>--}}
{{--            <th>Authors</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        @foreach($datasets as $dataset)--}}
{{--            <tr>--}}
{{--                <td>--}}
{{--                    <a href="{{route('public.datasets.show', [$dataset])}}">--}}
{{--                        {{$dataset->name}}--}}
{{--                    </a>--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    {{$dataset->summary}}--}}
{{--                </td>--}}
{{--                <td>--}}
{{--                    Authors here--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}
{{--@push('scripts')--}}
{{--    <script>--}}
{{--        $(document).ready(() => {--}}
{{--            $('#datasets').DataTable({--}}
{{--                stateSave: true,--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
{{--@endif--}}
