@props(["file", "fileSamples", "fileComputations"])
<div>
    @if(!is_null($fileSamples))
        <a class="action-link ml-2" data-toggle="collapse" href="#file-samples-{{ $file->id }}" role="button"
           aria-expanded="false" aria-controls="file-samples-{{ $file->id }}">Show Samples</a>
    @endif
    @if(!is_null($fileComputations))
        <a class="action-link ml-2" data-toggle="collapse" href="#file-computations-{{ $file->id }}" role="button"
           aria-expanded="false" aria-controls="file-computations-{{ $file->id }}">Show Computations</a>
    @endif
    <div class="row">
        @if(!is_null($fileSamples))
            <div class="col">
                <div class="collapse multi-collapse" id="file-samples-{{ $file->id }}">
                    <div class="card card-body">
                        <h5>Samples</h5>
                        @foreach($fileSamples as $sample)
                            <a class="ml-2"
                               href="{{route('projects.entities.show-spread', [$file->project_id, $sample['entity_id']])}}">
                                {{$sample['entity_name']}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        @if(!is_null($fileComputations))
            <div class="col">
                <div class="collapse multi-collapse" id="file-computations-{{ $file->id }}">
                    <div class="card card-body">
                        <h5>Computations</h5>
                        @foreach($fileComputations as $computation)
                            <a class="ml-2"
                               href="{{route('projects.computations.entities.show-spread', [$file->project_id, $computation['entity_id']])}}">
                                {{$computation['entity_name']}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>