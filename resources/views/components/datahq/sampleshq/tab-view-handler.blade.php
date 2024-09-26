<div>
    <x-datahq.view-controls :project="$project"
                            :tab="$tab"
                            :state-service="'sampleshq'"
                            :show-filters="$showFilters"/>

    <x-datahq.explorer.tab-subview-handler :project="$project"
                                           :tab="$tab"
                                           :state-service="'sampleshq'"
                                           :subview="$subview"/>
</div>
