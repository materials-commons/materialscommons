<x-datasets.kpi :dataset="$dataset" />

<div class="row g-3 align-items-start">
    <div class="col-12 col-xl-8">
        <x-datasets.overview-summary :dataset="$dataset" />

        <x-datasets.contents-overview :dataset="$dataset" />

        <x-datasets.analytics
            :dataset="$dataset"
            :file-description-types="$fileDescriptionTypes ?? []"
            :activities-group="$activitiesGroup ?? collect()"
        />

        <x-datasets.workflow-preview :dataset="$dataset" />

        <x-datasets.community-context :dataset="$dataset" />

        <x-datasets.citation-card :dataset="$dataset" />

        <x-display-markdown-file :file="$readme"></x-display-markdown-file>
    </div>

    <div class="col-12 col-xl-4">
        <x-datasets.sidebar
            :dataset="$dataset"
            :author-users="$authorUsers ?? null"
        />
    </div>
</div>
