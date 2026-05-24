@props([
    'ownedCount' => 0,
    'includedCount' => 0,
    'totalViews' => 0,
    'totalDownloads' => 0,
    'paperCount' => 0,
    'coauthorCount' => 0,
])

<div class="row g-2 mb-3">
    <div class="col-6 col-md-4 col-xl-2">
        <x-public.author.summary-card
            label="Owned"
            :value="$ownedCount"
            hint="datasets"
            color="primary"
            icon="fas fa-database"
        />
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <x-public.author.summary-card
            label="Included In"
            :value="$includedCount"
            hint="datasets"
            color="info"
            icon="fas fa-list"
        />
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <x-public.author.summary-card
            label="Views"
            :value="$totalViews"
            hint="on owned"
            color="success"
            icon="fas fa-eye"
        />
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <x-public.author.summary-card
            label="Downloads"
            :value="$totalDownloads"
            hint="on owned"
            color="warning"
            icon="fas fa-download"
        />
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <x-public.author.summary-card
            label="Papers"
            :value="$paperCount"
            hint="across datasets"
            color="secondary"
            icon="fas fa-file-alt"
        />
    </div>

    <div class="col-6 col-md-4 col-xl-2">
        <x-public.author.summary-card
            label="Co-authors"
            :value="$coauthorCount"
            hint="unique"
            color="danger"
            icon="fas fa-users"
        />
    </div>
</div>
