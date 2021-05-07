<a href="https://materials-commons.github.io/materials-commons-cli/html/manual/proj_init_clone.html#the-local-project-directory">
    How to setup local project directory
</a>
<pre>
# Once you have set up a local project on your system
mc ls .
@if ($directory->name != '/' && $directory->path == '/')
mc ls {{$directory->name}}
@elseif ($directory->name != '/' && $directory->path != '/')
mc ls {{Illuminate\Support\Str::replaceFirst('/', '', $directory->path)}}
@endif

# Make a new directory
mc mkdir myDir
</pre>