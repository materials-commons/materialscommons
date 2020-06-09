<ul>
    @foreach($entities as $entity)
        <li><a href="{{route('projects.entities.show', [$project, $entity])}}">{{$entity->name}}</a></li>
    @endforeach
</ul>