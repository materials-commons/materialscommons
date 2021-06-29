<div id="{{slugify($name)}}" class="container ml-4" hx-target="this" hx-swap="outerHTML">
    <div class="row">
        Showing details for {{$name}}
    </div>
    <div class="row">
        Details here
    </div>
    <a href="#" hx-get="{{route('projects.attributes.close-details-by-name', [$project, $name])}}" class="row">Close</a>
</div>