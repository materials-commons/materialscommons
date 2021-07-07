<h4>Query Builder</h4>
<a href="#" class="float-right" @click="toggleShowBuilder()">Close</a>
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
<a class="btn btn-success" href="#">Apply</a>
<div class="row mt-4">
    <div class="col-sm">
        <h4>Processes</h4>
        @include('partials.mql._processes-list')
    </div>
    <div class="col-sm">
        <h4>Process Attributes</h4>
        @include('partials.mql._process-attributes-list')
    </div>
    <div class="col-sm">
        <h4>Sample Attributes</h4>
        @include('partials.mql._sample-attributes-list')
    </div>
</div>
<a class="btn btn-success" href="#">Apply</a>
<hr>