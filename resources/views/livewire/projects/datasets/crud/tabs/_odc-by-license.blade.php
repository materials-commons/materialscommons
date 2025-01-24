<x-datasets.license-card>
    <x-slot:summary>
        <h5 class="card-title">Open Data Commons Attribution License (ODC-By)
            <a href="#" class="float-right cursor-pointer card-link grey-8"><i
                        class="fa fas fa-check"></i></a>
        </h5>
        <span>Summary</span>
        <p class="card-text">
            This license is more permissive, requiring only attribution, without imposing the "share-alike" condition
            or requiring openness of derivatives.
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
                    <span style="font-weight: bold">Share:</span>Distribute and use the data.
                </li>
                <li>
                    <span style="font-weight: bold">Create:</span>Develop works derived from the data.
                </li>
                <li>
                    <span style="font-weight: bold">Adapt:</span>Modify and enhance the data.
                </li>
            </ul>
            <p class="card-text">
                This comes with the following obligations:
            </p>
            <ul class="font-weight-normal">
                <li>
                    <span style="font-weight: bold">Attribution:</span>
                    <ul>
                        <li>
                            Proper credit must be provided for any <span
                                    style="font-weight: bold">public</span> use of the data or any works
                            derived from it.
                        </li>
                        <li>
                            Redistributed versions must clearly state the license and preserve the
                            notices
                            from the original database.
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    </x-slot:overview>
</x-datasets.license-card>