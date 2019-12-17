@component('components.card')
    @slot('header')
        Communities
    @endslot

    @slot('body')
        <table id="communities" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dataset->communities as $community)
                <tr>
                    <td>
                        <a href="{{route('communities.show', [$community])}}">
                            {{$community->name}}
                        </a>
                    </td>
                    <td>{{$community->description}}</td>
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
                $('#communities').DataTable({
                    stateSave: true,
                });
            });
        });
    </script>
@endpush