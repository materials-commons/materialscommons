<div>
        @if($tab == 'index')
                <x-datahq.view-controls :project="$project" :tab="$tab" :state-service="'sampleshq'"
                                        :show-filters="false"/>
        <x-projects.samples.samples-table :project="$project"/>
                <x-datahq.explorer.tab-subview-handler :project="$project" :tab="$tab" :state-service="'sampleshq'"/>
    @else
                <x-datahq.view-controls :project="$project" :tab="$tab" :state-service="'sampleshq'"
                                        :show-filters="true"/>
        <x-projects.samples.samples-table :project="$project"/>
                <x-datahq.explorer.tab-subview-handler :project="$project" :tab="$tab" :state-service="'sampleshq'"/>
    @endif
</div>
