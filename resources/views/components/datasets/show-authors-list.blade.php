@php
    $authorsByEmail = $dataset->authors()->get()->keyBy('email');
    $externalAuthorsByEmail = $dataset->externalAuthors()->get()->keyBy('email');
@endphp
<div class="form-group">
    <label for="authors">Authors</label>
    <ul class="list-inline form-control">
        @foreach($dataset->author_order as $author)
            <li class="list-inline-item">
                @if($authorsByEmail->has($author))
                    @php
                        $user = $authorsByEmail->get($author);
                    @endphp
                    <a href="{{route('datasets.user.published.show', [$user])}}">{{$user->name}}</a> ({{$user->email}})
                @else
                    @php
                        $externalUser = $externalAuthorsByEmail->get($author);
                    @endphp
                    <a href="{{route('datasets.external_user.published.show', [$externalUser])}}">
                        {{$externalUser->name}}
                    </a> ({{$externalUser->email}})
                @endif
            </li>
        @endforeach
    </ul>
</div>