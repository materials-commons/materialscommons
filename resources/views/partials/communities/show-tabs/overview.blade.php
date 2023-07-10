<form>
    <div class="form-group">
        <label for="name">Name</label>
        <p>{{$community->name}}
    </div>

    <div class="form-group">
        <label>Organizer</label>
        <p>
            {{$community->owner->name}}
            @if(!blank($community->owner->affiliations))
                ({{$community->owner->affiliations}})
            @endif
            <a href="mailto:{{$community->owner->email}}?subject={{$community->name}}">
                <i class="fas fa fa-envelope"></i>
            </a>
        </p>
    </div>

    <x-show-description :description="$community->description"/>

    <div class="form-group">
        <label for="tags">Tags</label>
        @if(!blank($tags))
            <ul class="list-inline">
                @foreach($tags as $tag => $count)
                    <li class="list-inline-item mt-1">
                        <a class="badge badge-success fs-11 td-none"
                           href="{{route('public.tags.search', ['tag' => $tag])}}">
                            {{$tag}}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="form-group">
        <label>Contributors</label>
        @if(!blank($contributors))
            <ul class="list-inline">
                @foreach($contributors as $c => $count)
                    <li class="list-inline-item mt-1">
                        <a href="#" class="no-underline">{{$c}}</a>
                        @if(!$loop->last)
                            ,
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</form>