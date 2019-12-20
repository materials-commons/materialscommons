@component('components.card')
    @slot('header')
        Communities
    @endslot

    @slot('body')
        <table id="dt-table" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dataset->experiments as $experiment)
                <tr>
                    <td>
                        <a href="{{route('projects.experiments.show', [$project, $experiment])}}">
                            {{$experiment->name}}
                        </a>
                    </td>
                    <td>{{$experiment->description}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).ready(() => {
                $('#dt-table').DataTable({
                    stateSave: true,
                });
            });
        });
    </script>
@endpush