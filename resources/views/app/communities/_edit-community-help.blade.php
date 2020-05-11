@switch(Route::getCurrentRoute()->getName())
    @case('projects.datasets.create-data')
    <p>
        Add or select files to include in your dataset.
    </p>
    @break

    @case('projects.datasets.workflows.create-data')
    <p>
        Add or select workflows to include in your dataset.
    </p>
    @break

    @case('projects.datasets.samples.create-data')
    <p>
        Add or select samples to include in your dataset.
    </p>
    @break

    @case('projects.datasets.activities.create-data')
    <p>
        Select processes to include in your dataset.
    </p>
    @break

    @default
    <p>
        Add or select datasets to include in your community.
    </p>
    @break
@endswitch