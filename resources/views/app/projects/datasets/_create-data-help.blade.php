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
        This should never appear.
    </p>
    @break
@endswitch

<p>
    When you are done you can <a href="#">Review</a> your dataset. If you would like to edit the details,
    such as tags, authors, etc... you can select the <a href="#">Edit Details</a> link here or
    above.
</p>