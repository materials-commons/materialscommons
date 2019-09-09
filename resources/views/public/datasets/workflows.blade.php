<div class="ml-2">
    <div id="workflow"></div>
</div>

@push('scripts')
    <script>
        $(document).ready(() => {
            let count = {!! $dataset->workflows()->count() !!};
            if (count !== 0) {
                let code = `{!! $dataset->workflows[0]->workflow !!}`;
                mcfl.drawFlowchart('workflow', code);
            }
        });
    </script>
@endpush