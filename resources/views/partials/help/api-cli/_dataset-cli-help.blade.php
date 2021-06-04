<a href="https://materials-commons.github.io/materials-commons-cli/html/manual/proj_init_clone.html#the-local-project-directory">
    How to setup local project directory
</a>
@if(isset($project))
<pre>
# Once you have set up a local project on your system
mc clone {{$project->id}}
cd '{{$project->name}}'

# List datasets in project
mc dataset

@if(isset($dataset))
# Get details on about dataset
mc dataset --id {{$dataset->id}} -d
@endif
</pre>
@endif