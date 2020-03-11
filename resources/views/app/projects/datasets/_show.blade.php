@component('components.item-details', ['item' => $dataset])
    @slot('top')
        <div class="form-group">
            <label for="authors">Authors and Affiliations</label>
            <input class="form-control" value="{{$dataset->authors}}" id="authors" type="text" readonly>
        </div>
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