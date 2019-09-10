<div class="ml-2">
    <div class="float-right">
        <a href="{{route('projects.show', ['id' => $project->id])}}" class="action-link">
            <i class="fas fa-fw fa-plus"></i>
        </a>
        <a href="{{route('projects.edit', ['id' => $project->id])}}" class="action-link">
            <i class="fas fa-fw fa-edit"></i>
        </a>
        <a data-toggle="modal" href="#project-delete-{{$project->id}}" class="action-link">
            <i class="fas fa-fw fa-trash-alt"></i>
        </a>
    </div>
    <div id="workflow"></div>
</div>

@push('scripts')
    <script>
        $(document).ready(() => {
            let count = {!! $experiment->workflows()->count() !!};
            let workflows = {!! $experiment->workflows !!};
            if (count !== 0) {
                let code = workflows[0].workflow;
                mcfl.drawFlowchart('workflow', code);
            }
            $('[id^=sub1]').click(function () {
                alert('info here');
            });
        });

        function myFunction(event, node) {
            console.log("You just clicked this node:", node);
        }

    </script>
@endpush