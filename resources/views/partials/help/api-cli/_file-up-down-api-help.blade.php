@if(isset($directory))
<pre>
# This will upload a file into a directory in your project
# upload_file(project_id, directory_id, path-to-file-to-upload)
c.upload_file({{$project->id}}, {{$directory->id}}, "/path/to/file/to/upload")

# To download a file you can either give the id of the file, or the path to the file on the server
c.download_file({{$project->id}}, file_id, "path-to-download-to")

# Or you can specify a path in the project
c.download_file_by_path({{$project->id}}, "{{$directory->path}}/example-file.txt", "path-to-download-to")
</pre>
@endif