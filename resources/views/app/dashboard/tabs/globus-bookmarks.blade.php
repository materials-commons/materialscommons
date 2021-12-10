<p>
    This page lists all the Materials Commons links to start a Globus upload/download for a project. If you bookmark
    this page in your browser then you can easily go to the Globus web page for your project.
</p>
<p>
    <b>Please note</b> that you
    cannot bookmark the Globus page as Materials Commons has to perform some setup in order to give Globus access
    to your project.
</p>
<ul class="list-unstyled ml-4">
    @foreach($projects as $project)
        <li>
            <a href="{{route('projects.globus.bookmark', [$project])}}">{{$project->name}}</a>
        </li>
    @endforeach
</ul>