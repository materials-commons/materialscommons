<div>
    <ul>
        @foreach($projects as $project)
            <li>
                <span class="fs-10 grey-5">Name: {{$project->name}}</span>
                <span class="fs-10 grey-5 ml-3">ID: {{$project->id}}</span>
                <span class="fs-10 grey-5 ml-3">Owner Email: {{$project->owner->email}}</span>
                <span class="fs-10 grey-5 ml-3">Owner Name: {{$project->owner->name}}</span>
            </li>
        @endforeach
    </ul>
</div>