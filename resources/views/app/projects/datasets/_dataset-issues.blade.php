@if($hasErrorsWarningsAndOrRecommendations)
    <div x-data="initDatasetIssues()">
        <a href="#" @click="showIssues=!showIssues" x-text="showIssuesLinkText()">
        </a>
        <ul class="list-unstyled ml-3 mt-2" x-show="showIssues" style="display: none">
            @if(!$dataset->hasSelectedFiles())
                <template x-if="!dataset.hasFiles">
                    <li id="error-files">
                        <x-error-icon/>
                        Your dataset has no files. In order to publish a dataset you must have files.
                    </li>
                </template>
            @else
                <template x-if="dataset.hasFiles">
                    <li id="error-files" style="display: none">
                        <x-error-icon/>
                        Your dataset has no files. In order to publish a dataset you must have files.
                    </li>
                </template>
            @endif
            <template x-if="dataset.doi === ''">
                <li class="mt-2">
                    <span>
                        <x-warning-icon/>
                        It is recommended you assign a DOI. A DOI gives you a permanent identifier.
                        It makes it easy to include a link
                        in a paper. Your DOI will update if your dataset moves to a different URL.
                    </span>
                </li>
            </template>
            <template x-if="dataset.description === ''">
                <li class="mt-2">
                    <x-error-icon/>
                    Your dataset has no description. A description is required.
                </li>
            </template>
            <template x-if="dataset.description !== '' && dataset.description.length < 50">
                <li class="mt-2">
                    <x-warning-icon/>
                    Your description is less than 50 characters. You should include a description of
                    atleast 50 characters otherwise your dataset won't show up in google dataset search.
                </li>
            </template>
                <template x-if="dataset.summary === ''">
                    <li class="mt-2">
                        <x-warning-icon/>
                        A summary shows up in the list of all datasets. It allows researchers to more
                        easily find your dataset. It is recommended that you give your dataset a short summary.
                    </li>
                </template>
                <template x-if="dataset.license === '' || dataset.license === 'No License'">
                    <li class="mt-2">
                        <x-warning-icon/>
                        A license can control how your dataset is used. It is recommended that you
                        choose a license for your dataset.
                    </li>
                </template>
                <template x-if="dataset.authors.length == 0">
                    <li class="mt-2">
                        <x-warning-icon/>
                        Your dataset has no authors. An authors list helps others evaluate your dataset.
                        It is recommended that you include authors.
                    </li>
                </template>
                <template x-if="dataset.funding === ''">
                    <li class="mt-2">
                        <x-warning-icon/>
                        Your funding field is blank. If your research was funded through public or private
                        means it is recommended that you include an acknowledgement.
                    </li>
                </template>
        </ul>
    </div>

    @push('scripts')
        <script>
            function initDatasetIssues() {
                return {
                    showIssues: false,
                    dataset: {
                        hasFiles: {{$dataset->hasSelectedFiles() ? 1 : 0}},
                        doi: "{{$dataset->doi}}",
                        description: "{{$dataset->description}}",
                        summary: "{{$dataset->summary}}",
                        license: "{{$dataset->license}}",
                        authors: @json($dataset->ds_authors),
                        funding: "{{$dataset->funding}}",
                    },
                    showIssuesLinkText() {
                        if (!this.showIssues) {
                            return "Show issues, warnings and recommendations";
                        }

                        return "Hide issues, warnings and recommendations";
                    },
                };
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .fa-stack {
                font-size: 0.7em;
            }

            i {
                vertical-align: middle;
            }
        </style>
    @endpush
@endif