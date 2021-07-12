<h4>Query Builder <a href="#" class="ml-2 fs-7" @click="toggleShowBuilder()">close</a></h4>

<br>
<div class="container">
    <div class="row">
        <div class="col-sm-8">
            @include('partials.entities.mql._mql-textbox')
        </div>
    </div>
</div>
<hr>
<br>
<a class="btn btn-success" href="#"
   hx-post="{{route('projects.entities.mql.show', $project)}}"
   hx-include="#mql-selection"
   hx-target="#mql-query">
    Apply
</a>
<p>Showing processes and attributes that are available from the current selection.</p>
<form class="row mt-4" id="mql-selection" action="{{route('projects.entities.mql.run', [$project])}}" method="POST">

    @csrf
    <div class="col-sm">
        <h4>Processes</h4>
        @include('partials.mql._processes-list')
    </div>
    <div class="col-sm">
        <h4>Process Attributes</h4>
        <x-mql.attribute-query-list :attrs="$processAttributes" :project="$project" :form-var-name="'process_attrs'"
                                    :details-route-name="'projects.activities.attributes.show-details-by-name'"/>
    </div>
    <div class="col-sm">
        <h4>Sample Attributes</h4>
        <x-mql.attribute-query-list :attrs="$sampleAttributes" :project="$project" :form-var-name="'sample_attrs'"
                                    :details-route-name="'projects.entities.attributes.show-details-by-name'"/>
    </div>
</form>
<a class="btn btn-success" href="#"
   hx-post="{{route('projects.entities.mql.show', $project)}}"
   hx-include="#mql-selection"
   hx-target="#mql-query">
    Apply
</a>
<hr>