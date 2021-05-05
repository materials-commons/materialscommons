<pre>
# Get experiment details
experiment = c.get_experiment({{$experiment->id}})

# Delete experiment
c.delete_experiment({{$project->id}}, {{$experiment->id}})
</pre>