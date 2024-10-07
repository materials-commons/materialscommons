<div>
    <x-datahq.view-controls :project="$project"
                            :tab="$activeTab"
                            :state-service="'sampleshq'"
                            :show-filters="$showFilters"/>

    <x-datahq.explorer.tab-subviews :project="$project"
                                    :tab="$activeTab"/>

    <x-datahq.explorer.tab-subview-handler :project="$project"
                                           :tab="$activeTab"
                                           :subview="$activeSubview"/>
</div>
