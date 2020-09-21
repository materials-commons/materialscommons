@component('components.item-details', ['item' => $dataset])
    @slot('top')
        <x-datasets.show-authors-list :dataset="$dataset"></x-datasets.show-authors-list>
    @endslot


    <span class="ml-4">Published:
                    @isset($dataset->published_at)
            {{$dataset->published_at->diffForHumans()}}
        @else
            Not Published
        @endisset
                </span>

    @slot('bottom')
        <div class="form-group">
            <label for="doi">DOI</label>
            <input class="form-control" id="doi" type="text" value="{{$dataset->doi}}" readonly>
        </div>
        <div class="form-group">
            <label for="license">License</label>
            <input class="form-control" id="license" type="text" value="{{$dataset->license}}" readonly>
        </div>
        <div class="form-group">
            <label for="tags">Tags</label>
            <div class="form-control" id="tags" readonly>
                @foreach($dataset->tags as $tag)
                    <span class="badge badge-success fs-11">{{$tag->name}}</span>
                @endforeach
            </div>
        </div>
    @endslot
@endcomponent