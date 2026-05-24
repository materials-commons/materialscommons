{{-- resources/views/components/dashboard/my-research/licenses/overview.blade.php --}}
@props([
    'datasets' => collect(),
    'projects' => collect(),
])

@php
    $datasets = collect($datasets);
    $projects = collect($projects);

    $normalizeLicense = function ($license) {
        $license = trim((string) $license);

        return blank($license) ? 'Missing License' : $license;
    };

    $isCustomOrUnknown = function ($license) {
        $license = strtolower(trim((string) $license));

        if (blank($license)) {
            return false;
        }

        return str_contains($license, 'custom')
            || str_contains($license, 'unknown')
            || str_contains($license, 'other')
            || str_contains($license, 'not specified')
            || str_contains($license, 'n/a');
    };

    $publishedDatasets = $datasets->filter(fn($dataset) => filled($dataset->published_at ?? null));
    $draftDatasets = $datasets->reject(fn($dataset) => filled($dataset->published_at ?? null));

    $missingLicense = $datasets->filter(fn($dataset) => blank($dataset->license ?? null));
    $customOrUnknownLicense = $datasets->filter(fn($dataset) => $isCustomOrUnknown($dataset->license ?? null));
    $publicMissingLicense = $publishedDatasets->filter(fn($dataset) => blank($dataset->license ?? null));
    $draftMissingLicense = $draftDatasets->filter(fn($dataset) => blank($dataset->license ?? null));

    $licenseDistribution = $datasets
        ->map(fn($dataset) => $normalizeLicense($dataset->license ?? null))
        ->countBy()
        ->sortDesc();

    $licensesByProject = $datasets
        ->groupBy(fn($dataset) => $dataset->project?->name ?? 'No Project')
        ->map(function ($projectDatasets) use ($normalizeLicense) {
            return collect($projectDatasets)
                ->map(fn($dataset) => $normalizeLicense($dataset->license ?? null))
                ->countBy()
                ->sortDesc();
        })
        ->sortKeys();

    $licenseRows = $datasets->map(function ($dataset) use ($normalizeLicense, $isCustomOrUnknown) {
        $isPublished = filled($dataset->published_at ?? null);
        $hasLicense = filled($dataset->license ?? null);
        $isCustomUnknown = $isCustomOrUnknown($dataset->license ?? null);

        $issues = collect();

        if (!$hasLicense) {
            $issues->push('Missing license');
        }

        if ($isCustomUnknown) {
            $issues->push('Custom / unknown license');
        }

        if ($isPublished && !$hasLicense) {
            $issues->push('Public dataset missing license');
        }

        if (!$isPublished && !$hasLicense) {
            $issues->push('Draft dataset missing license');
        }

        return [
            'dataset' => $dataset,
            'license' => $normalizeLicense($dataset->license ?? null),
            'status' => $isPublished ? 'Published' : 'Draft',
            'has_license' => $hasLicense,
            'is_custom_unknown' => $isCustomUnknown,
            'issues' => $issues,
        ];
    });
@endphp

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.licenses.summary-card
            label="Missing License"
            :value="$missingLicense->count()"
            hint="all datasets"
            color="danger"
        />
    </div>

    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.licenses.summary-card
            label="Custom / Unknown"
            :value="$customOrUnknownLicense->count()"
            hint="needs review"
            color="warning"
        />
    </div>

    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.licenses.summary-card
            label="Public Missing"
            :value="$publicMissingLicense->count()"
            hint="published datasets"
            color="danger"
        />
    </div>

    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.licenses.summary-card
            label="Draft Missing"
            :value="$draftMissingLicense->count()"
            hint="draft datasets"
            color="secondary"
        />
    </div>

    <div class="col-6 col-md-3 col-xl">
        <x-dashboard.my-research.licenses.summary-card
            label="License Types"
            :value="$licenseDistribution->reject(fn($count, $license) => $license === 'Missing License')->count()"
            hint="in use"
            color="info"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-5">
        <x-dashboard.my-research.licenses.charts.distribution
            :license-distribution="$licenseDistribution"
            :license-rows="$licenseRows"
        />
    </div>

    <div class="col-12 col-xl-7">
        <x-dashboard.my-research.licenses.charts.by-project
            :licenses-by-project="$licensesByProject"
        />
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12">
        <x-dashboard.my-research.licenses.analytics
            :datasets="$datasets"
            :published-datasets="$publishedDatasets"
            :draft-datasets="$draftDatasets"
            :missing-license="$missingLicense"
            :custom-or-unknown-license="$customOrUnknownLicense"
            :public-missing-license="$publicMissingLicense"
            :draft-missing-license="$draftMissingLicense"
            :license-distribution="$licenseDistribution"
        />
    </div>
</div>

<x-dashboard.my-research.licenses.table
    :license-rows="$licenseRows"
/>
