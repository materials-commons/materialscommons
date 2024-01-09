<div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-7">
            <x-mql.query-builder.activity-selector :project="$project" :activities="$activities" :category="$category"/>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-7">
            <x-mql.query-builder.activity-attribute-selector :project="$project"
                                                             :process-attributes="$processAttributes"
                                                             :category="$category"/>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-7">
            <x-mql.query-builder.entity-attribute-selector :project="$project" :entity-attributes="$sampleAttributes"
                                                           :category="$category"/>
        </div>
    </div>
</div>