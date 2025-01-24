<x-datasets.license-card>
    <x-slot:summary>
        <h5 class="card-title">Open Data Commons Public Domain Dedication and License (PDDL)
            <a href="#" class="float-right cursor-pointer card-link grey-8"><i
                        class="fa fas fa-check"></i></a>
        </h5>
        <span>Summary</span>
        <p class="card-text">
            The terms under the PDDL (Public Domain Dedication and License) provide complete freedom to
            use the data. No conditions or restrictions are placed on the use of the data.
        </p>
    </x-slot:summary>
    <x-slot:overview>
        @if(false)
            <span>Overview</span>
            <p class="card-text">
                Users are free to:
            </p>
            <ul class="font-weight-normal">
                <li>
                    <span style="font-weight: bold">Share:</span>Copy, distribute and use the data.
                </li>
                <li>
                    <span style="font-weight: bold">Create:</span>Develop works derived from the data.
                </li>
                <li>
                    <span style="font-weight: bold">Adapt:</span>Modify, transform and build upon the
                    data.
                </li>
            </ul>
            <p class="card-text">
                This comes with the following obligations:
            </p>
            <ul class="font-weight-normal">
                <li>
                    <span style="font-weight: bold">No Restrictions:</span> The license (PDDL) does not
                    impose any conditions or restrictions on how the data is used.
                </li>
            </ul>
        @endif
    </x-slot:overview>
</x-datasets.license-card>