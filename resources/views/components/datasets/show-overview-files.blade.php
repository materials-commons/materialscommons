<div class="row">
    @if(!is_null($dataset->file1_id))
        <div class="col-lg-6 col-md-10">
            <div class="border m-1">
                @include('partials.files._display-file', [
                        'displayRoute' => route('public.datasets.files.display', [$dataset, $dataset->file1]),
                        'file' => $dataset->file1,
                    ])
            </div>
        </div>
    @endif

    @if(!is_null($dataset->file2_id))
        <div class="col-lg-6 col-md-10">
            <div class="border m-1">
                @include('partials.files._display-file', [
                        'displayRoute' => route('public.datasets.files.display', [$dataset, $dataset->file2]),
                        'file' => $dataset->file2
                    ])
            </div>
        </div>
    @endif

    @if(!is_null($dataset->file3_id))
        <div class="col-lg-6 col-md-10">
            <div class="border m-1">
                @include('partials.files._display-file', [
                        'displayRoute' => route('public.datasets.files.display', [$dataset, $dataset->file3]),
                        'file' => $dataset->file3
                    ])
            </div>
        </div>
    @endif

    @if(!is_null($dataset->file4_id))
        <div class="col-lg-6 col-md-10">
            <div class="border m-1">
            @include('partials.files._display-file', [
                    'displayRoute' => route('public.datasets.files.display', [$dataset, $dataset->file4]),
                    'file' => $dataset->file4
                ])
            </div>
        </div>
    @endif

    @if(!is_null($dataset->file5_id))
        <div class="col-lg-6 col-md-10">
            <div class="border m-1">
            @include('partials.files._display-file', [
                    'displayRoute' => route('public.datasets.files.display', [$dataset, $dataset->file5]),
                    'file' => $dataset->file5
                ])
            </div>
        </div>
    @endif
</div>