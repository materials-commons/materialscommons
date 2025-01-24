<x-datasets.license-card>
    <x-slot:summary>
        <h5 class="card-title">Open Data Commons Database License (ODbL)
            <a href="#" class="float-right cursor-pointer card-link grey-8"><i
                        class="fa fas fa-check"></i></a>
        </h5>
        <span>Summary</span>
        <p class="card-text">
            This license ensures openness and collaboration by requiring that derivative works follow the same open
            licensing.
        </p>
    </x-slot:summary>
    <x-slot:overview>
        @if(false)
            <span>Overview</span>
            <p class="card-text">
                Users are free to:
            </p>
            <ul class="font-weight-normal">
                <li><span style="font-weight: bold">Share:</span> Copy, distribute and use the data.
                </li>
                <li><span style="font-weight: bold">Create:</span> produce works from the data.</li>
                <li><span style="font-weight: bold">Adapt:</span> Modify, transform and build upon the
                    data.
                </li>
            </ul>

            <p class="card-text">
                This comes with the following obligations:
            </p>
            <ul class="font-weight-normal">
                <li>
                    <span style="font-weight: bold">Attribute:</span>
                    <ul>
                        <li>
                            You must give credit for any public use of the data or works derived from it
                            in
                            the manner specified in the ODbL.
                        </li>
                        <li>
                            Any redistribution must clarify the license and retain notices on the
                            original
                            data.
                        </li>
                    </ul>
                </li>
                <li>
                    <span style="font-weight: bold">Share-Alike:</span>
                    <ul>
                        <li>
                            If you publicly use or share an adapted version of the data, it must also be
                            licensed under the ODbL.
                        </li>
                    </ul>
                </li>
                <li>
                    <span style="font-weight: bold">Keep open:</span>
                    <ul>
                        <li>
                            You can redistribute the data with restrictions (e.g., DRM), but must also
                            provide a version without such restrictions.
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
    </x-slot:overview>
</x-datasets.license-card>