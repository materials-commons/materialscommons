@props(["file", "fileSamples", "fileComputations"])
<div>
    @if(!is_null($fileSamples))
        <a class="action-link ml-2" data-toggle="collapse" href="#file-samples-{{ $file->id }}" role="button"
           aria-expanded="false" aria-controls="file-samples-{{ $file->id }}">Samples</a>
    @endif
    @if(!is_null($fileComputations))
        <a class="action-link ml-2" data-toggle="collapse" href="#file-computations-{{ $file->id }}" role="button"
           aria-expanded="false" aria-controls="file-computations-{{ $file->id }}">Computations</a>
    @endif
    <div class="row">
        @if(!is_null($fileSamples))
            <div class="col">
                <div class="collapse multi-collapse" id="file-samples-{{ $file->id }}">
                    <div class="card card-body">
                        <h5>Samples</h5>
                        @foreach($fileSamples as $sample)
                            {{$sample['entity_name']}}
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        @if(!is_null($fileComputations))
            <div class="col">
                <div class="collapse multi-collapse" id="file-computations-{{ $file->id }}">
                    <div class="card card-body">
                        <h3>Computations</h3>
                        @foreach($fileComputations as $computation)
                            {{$computation['entity_name']}}
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>