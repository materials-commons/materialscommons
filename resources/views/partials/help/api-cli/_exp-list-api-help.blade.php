<pre>
# Get all experiments for a project
experiments = c.get_all_experiments({{$project->id}})

# Create a new experiment in project
experiment = c.create_experiment({{$project->id}}, "new-experiment")
</pre>