<a href="https://materials-commons.github.io/materials-commons-cli/html/manual/proj_init_clone.html#the-local-project-directory">
    How to setup local project directory
</a>
<pre>
# Once you have set up a local project on your system
mc ls .
@if ($directory->name != '/')
    @if($directory->path == '/')
mc ls /{{$directory->name}}
    @else
mc ls {{$directory->path}}/{{$directory->name}}
    @endif
@endif

# Make a new directory
mc mkdir myDir
</pre>