<pre>
# Get details for file
file = c.get_file({{$project->id}}, {{$file->id}})

# Get details for file by path
@if($file->directory->path == '/')
        file = c.get_file({{$project->id}}, /{{$file->name}})
    @else
        file = c.get_file({{$project->id}}, {{$file->directory->path}}/{{$file->name}})
    @endif

# Delete file
c.delete_file({{$project->id}}, {{$file->id}})

# Get file versions
versions = c.get_file_versions({{$project->id}}, {{$file->id}})
</pre>