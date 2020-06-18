@component('components.card')
    @slot('header')
        Samples
    @endslot

    @slot('body')
        @include('partials.entities._entities-with-used-activities-table')
        {{--        <table id="entities" class="table table-hover" style="width:100%">--}}
        {{--            <thead>--}}
        {{--            <tr>--}}
        {{--                <th>Name</th>--}}
        {{--                <th>Description</th>--}}
        {{--            </tr>--}}
        {{--            </thead>--}}
        {{--            <tbody>--}}
        {{--            @foreach($dataset->entities as $entity)--}}
        {{--                <tr>--}}
        {{--                    <td>--}}
        {{--                        <a href="{{route('projects.entities.show', [$project, $entity])}}">--}}
        {{--                            {{$entity->name}}--}}
        {{--                        </a>--}}
        {{--                    </td>--}}
        {{--                    <td>{{$entity->description}}</td>--}}
        {{--                </tr>--}}
        {{--            @endforeach--}}
        {{--            </tbody>--}}
        {{--        </table>--}}
    @endslot
@endcomponent

{{--@push('scripts')--}}
{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            $(document).ready(() => {--}}
{{--                $('#entities').DataTable({--}}
{{--                    stateSave: true,--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}