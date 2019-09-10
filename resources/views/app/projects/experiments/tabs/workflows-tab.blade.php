<div class="ml-2">
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