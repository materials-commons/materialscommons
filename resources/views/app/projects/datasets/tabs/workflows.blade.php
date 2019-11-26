@component('components.card')
    @slot('header')
        Workflows
    @endslot

    @slot('body')
        <table id="workflows" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @foreach($dataset->workflows as $workflow)
                <tr>
                    <td>
                        <a href="#">
                            {{$workflow->name}}
                        </a>
                    </td>
                    <td>{{$workflow->description}}</td>
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
                $('#workflows').DataTable({
                    stateSave: true,
                });
            });
        });
    </script>
@endpush