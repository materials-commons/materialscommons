<div class="ml-2">
    <div id="workflow"></div>
</div>

@push('scripts')
    <script>
        $(document).ready(() => {
            let count = {!! $dataset->workflows()->count() !!};
            let workflows = {!! $dataset->workflows !!};
            if (count !== 0) {
                let code = workflows[0].workflow;
                mcfl.drawFlowchart('workflow', code);
            }
        });
    </script>
@endpush