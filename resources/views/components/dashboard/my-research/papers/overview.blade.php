@props([
    'datasets' => collect(),
    'projects' => collect(),
])

@php
    $datasets = collect($datasets);
    $projects = collect($projects);

    $paperMap = [];

    foreach ($datasets as $dataset) {
        foreach (collect($dataset->papers ?? collect()) as $paper) {
            if (!isset($paperMap[$paper->id])) {
                $paperMap[$paper->id] = [
                    'paper' => $paper,
                    'datasets' => collect(),
                ];
            }

            $paperMap[$paper->id]['datasets']->push($dataset);
        }
    }

    $paperItems = collect(array_values($paperMap))
        ->sortBy(fn($item) => strtolower((string) ($item['paper']->name ?? '')))
        ->values();

    $papers = $paperItems->pluck('paper');

    $papersMissingDoi = $paperItems->filter(fn($item) => blank($item['paper']->doi ?? null));

    $datasetsWithPapers = $datasets->filter(fn($dataset) => collect($dataset->papers ?? collect())->isNotEmpty());
    $datasetsWithoutPapers = $datasets->filter(fn($dataset) => collect($dataset->papers ?? collect())->isEmpty());

    $datasetsCitedByPapers = $datasetsWithPapers;

    $datasetsReadyToPublishButNotPublic = $datasets->filter(function ($dataset) {
        return blank($dataset->published_at ?? null)
            && filled($dataset->name ?? null)
            && filled($dataset->description ?? null)
            && filled($dataset->license ?? null)
            && filled($dataset->doi ?? null)
            && filled($dataset->ds_authors ?? null);
    });

    $datasetsWithPublicationMetadataIncomplete = $datasets->filter(function ($dataset) {
        return blank($dataset->published_at ?? null)
            || blank($dataset->doi ?? null)
            || blank($dataset->license ?? null)
            || blank($dataset->description ?? null)
            || blank($dataset->ds_authors ?? null)
            || collect($dataset->papers ?? collect())->isEmpty();
    });

    $papersByProject = $paperItems
        ->flatMap(function ($item) {
            return $item['datasets']->map(function ($dataset) use ($item) {
                return [
                    'project' => $dataset->project?->name ?? 'No Project',
                    'paper_id' => $item['paper']->id,
                ];
            });
        })
        ->groupBy('project')
        ->map(fn($rows) => collect($rows)->pluck('paper_id')->unique()->count())
        ->sortDesc();

    $papersByOwner = $papers
        ->groupBy(fn($paper) => $paper->owner?->name ?? 'Unknown User')
        ->map(fn($ownerPapers) => collect($ownerPapers)->unique('id')->count())
        ->sortDesc();

    $paperRows = $paperItems->map(function ($item) {
        $paper = $item['paper'];
        $linkedDatasets = collect($item['datasets'])->unique('id')->values();

        $missing = collect();

        if (blank($paper->doi ?? null)) {
            $missing->push('DOI');
        }

        if (blank($paper->reference ?? null)) {
            $missing->push('reference');
        }

        if (blank($paper->url ?? null)) {
            $missing->push('URL');
        }

        if ($linkedDatasets->isEmpty()) {
            $missing->push('linked dataset');
        }

        return [
            'paper' => $paper,
            'datasets' => $linkedDatasets,
            'missing' => $missing,
            'projects' => $linkedDatasets
                ->map(fn($dataset) => $dataset->project?->name)
                ->filter()
                ->unique()
                ->values(),
        ];
    });
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.papers.summary-card
            label="Papers"
            :value="$papers->count()"
            hint="linked publications"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.papers.summary-card
            label="Linked Datasets"
            :value="$datasetsWithPapers->count()"
            hint="have papers"
            color="success"
        />
    </div>

    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.papers.summary-card
            label="Datasets Without Paper"
            :value="$datasetsWithoutPapers->count()"
            hint="missing citation"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.papers.summary-card
            label="Papers Missing DOI"
            :value="$papersMissingDoi->count()"
            hint="needs DOI"
            color="danger"
        />
    </div>

    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.papers.summary-card
            label="Ready, Not Public"
            :value="$datasetsReadyToPublishButNotPublic->count()"
            hint="dataset candidates"
            color="info"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-7">
        <x-dashboard.my-research.papers.analytics
            :papers="$papers"
            :paper-items="$paperItems"
            :papers-missing-doi="$papersMissingDoi"
            :datasets="$datasets"
            :datasets-with-papers="$datasetsWithPapers"
            :datasets-without-papers="$datasetsWithoutPapers"
            :datasets-cited-by-papers="$datasetsCitedByPapers"
            :datasets-ready-to-publish-but-not-public="$datasetsReadyToPublishButNotPublic"
            :datasets-with-publication-metadata-incomplete="$datasetsWithPublicationMetadataIncomplete"
            :papers-by-project="$papersByProject"
            :papers-by-owner="$papersByOwner"
        />
    </div>

    <div class="col-12 col-xl-5">
        <x-dashboard.my-research.papers.needs-attention
            :paper-rows="$paperRows"
            :datasets-without-papers="$datasetsWithoutPapers"
            :datasets-ready-to-publish-but-not-public="$datasetsReadyToPublishButNotPublic"
            :datasets-with-publication-metadata-incomplete="$datasetsWithPublicationMetadataIncomplete"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <x-dashboard.my-research.papers.charts.by-project
            :papers-by-project="$papersByProject"
        />
    </div>

    <div class="col-12 col-xl-6">
        <x-dashboard.my-research.papers.charts.by-owner
            :papers-by-owner="$papersByOwner"
        />
    </div>
</div>

<x-dashboard.my-research.papers.table
    :paper-rows="$paperRows"
    :datasets-without-papers="$datasetsWithoutPapers"
/>
