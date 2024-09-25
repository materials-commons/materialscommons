<div>
        @if($tab == 'index')
                <x-datahq.view-controls :project="$project"
                                        :tab="$tab"
                                        :state-service="'sampleshq'"
                                        :show-filters="false"/>

                <x-datahq.explorer.tab-subview-handler :project="$project"
                                                       :tab="$tab"
                                                       :state-service="'sampleshq'"
                                                       :subview="$subview"/>

                {{--        <x-projects.samples.samples-table :project="$project"/>--}}
    @else
                <x-datahq.view-controls :project="$project"
                                        :tab="$tab"
                                        :state-service="'sampleshq'"
                                        :show-filters="true"/>

                <x-datahq.explorer.tab-subview-handler :project="$project"
                                                       :tab="$tab"
                                                       :state-service="'sampleshq'"
                                                       :subview="$subview"/>

                {{--        <x-projects.samples.samples-table :project="$project"/>--}}
    @endif
</div>
