@if (isset($project))
<pre>
# You can optionally clone the project locally
mc clone {{$project->id}}

mc proj --id {{$project->id}}
</pre>
@else
<pre>
# List projects
mc proj
</pre>
@endif