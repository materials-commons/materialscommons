<h4>Query Builder <a href="#" class="ml-2 fs-7" @click="toggleShowBuilder()">close</a></h4>

<br>
<div class="container">
    <div class="row">
        <div class="col-sm-8" id="mql-query">
            @include('partials.mql._mql-textbox')
        </div>
    </div>
</div>
<hr>
<p>Showing processes and attributes that are available from the current selection.</p>
<form class="row mt-4" id="mql-selection" action="{{route('projects.entities.mql.run', [$project])}}" method="POST"
      autocomplete="off">

    @csrf
    <div class="col-sm">
        <h4>In Process</h4>
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
<hr>

@push('scripts')
    <script>
        if (typeof fireEvent === 'undefined') {
            function fireEvent(id) {
                let event = new Event('changed.bs.select');
                document.querySelector(id).dispatchEvent(event);
            }
        }
    </script>
@endpush