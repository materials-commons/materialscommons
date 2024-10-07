<div>
    <x-datahq.explorer.tabs :project="$project"/>
    <br/>
    <div>
        <x-datahq.sampleshq.tab-view-handler :project="$project"
                                             :active-tab="$activeTab"
                                             :active-subview="$activeSubview"/>
    </div>
</div>