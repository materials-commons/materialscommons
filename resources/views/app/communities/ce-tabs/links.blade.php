@component('components.card-white')
    @slot('header')
        Recommend Practices Links
        <a class="float-end action-link mr-2" href="{{route('communities.links.create', [$community])}}">
            <i class="fas fa-fw fa-plus mr-2"></i>Add Link
        </a>
    @endslot

    @slot('body')
        <table id="links" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($community->links as $link)
                <tr>
                    <td>
                        <a href="{{$link->url}}" target="_blank">
                            <i class="fa-fw fas mr-2 fa-external-link-alt"></i>{{$link->name}}
                        </a>
                    </td>
                    <td>{{$link->summary}}</td>
                    <td>
                        <a class="action-link" href="{{route('communities.links.edit-link', [$community, $link])}}">
                            <i class="fas fa-fw fa-edit"></i>
                        </a>
                        <a class="action-link" href="{{route('communities.links.delete', [$community, $link])}}">
                            <i class="fas fa-fw fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#links').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });
    </script>
@endpush
