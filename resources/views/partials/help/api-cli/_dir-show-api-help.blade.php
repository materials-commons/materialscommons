<pre>
# List directory
dir_listing = c.list_directory({{$project->id}}, {{$directory->id}})

# List directory by path
dir_list = c.list_directory({{$project->id}}, {{$directory->path}})

@if ($directory->path != "/")
        # Create a new directory
        new_dir = c.create_directory({{$project->id}}, {{$directory->id}}, {{$directory->directory_id}})

        # Rename directory
        updated_dir = c.rename_directory({{$project->id}}, {{$directory->id}}, "new-dir-name")

        # Delete directory
        c.delete_directory({{$project->id}}, {{$directory->id}})
    @endif
</pre>