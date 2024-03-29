@if(isset($dataset))
<pre>
# Get dataset details
dataset = c.get_dataset({{$project->id}}, {{$dataset->id}})

# Import dataset into a new project. This will import all the files for the dataset
# into the project under the given directory. The dataset must have been published.
c.import_dataset({{$dataset->id}}, "project-id to import into", "{{$dataset->name}}")

# Get file objects for a dataset
files = c.get_dataset_files({{$project->id}}, {{$dataset->id}})

# Delete dataset
c.delete_dataset({{$project->id}}, {{$dataset->id}})

# Publish dataset so that it shows up in the public list of published datasets
dataset = c.publish_dataset({{$project->id}}, {{$dataset->id}})

# Unpublish a previously published dataset
dataset = c.unpublish_dataset({{$project->id}}, {{$dataset->id}})
</pre>
@endif