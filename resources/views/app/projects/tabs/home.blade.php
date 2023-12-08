<x-show-standard-details :item="$project">
    <a class="ml-3 fs-10" href="{{route('projects.users.index', [$project])}}">
        {{$project->team->members->count()}} @choice("Member|Members", $project->team->members->count())
    </a>
    <a class="ml-3 fs-10" href="{{route('projects.users.index', [$project])}}">
        {{$project->team->admins->count()}} @choice("Admin|Admins", $project->team->admins->count())
    </a>
    <span class="ml-3 fs-10 grey-5">Size: {{formatBytes($project->size)}}</span>
    <span class="ml-3 fs-10 grey-5">Slug: {{$project->slug}}</span>
</x-show-standard-details>


{{--<p class="col-7">--}}
{{--    Materials Commons projects contain both structured data and unstructured data. Materials Commons--}}
{{--    understands data imported from spreadsheets and contains tools for viewing and querying this data.--}}
{{--</p>--}}

<div class="row">
    <div class="card-deck">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><strong>Files</strong></h5>
                <hr/>
                <p class="card-text">
                    Project files are backed up, versioned, and available to all project collaborators.
                    Most file types can be previewed in the project website, including thumbnail summaries
                    of image files. Links to project files can be sent to collaborators to conveniently
                    and privately share project data and results.
                </p>
                <p>
                    Several methods are available for transfering files, including through this website,
                    through the Materials Commons command line program or API, and using the Globus transfer service.
                </p>
                <div class="card-text">
                    <ul class="list-unstyled ml-3">
                        <li>
                            <a href="{{route('projects.folders.index', [$project])}}">View project files</a>
                        </li>

                        <li>
                            <a href="{{route('projects.upload-files', [$project])}}">Website upload</a>
                        </li>

                        @if(false)
                            <li>
                                <a href="#">Globus file transfer</a>
                                <small class="row text-muted ml-3">Upload/download files using&nbsp;<a
                                            href="https://globus.org" target="_blank">Globus</a>.
                                </small>
                            </li>
                        @endif

                        <li>
                            <a href="{{route('projects.globus.uploads.index', [$project])}}">Globus upload files</a>
                        </li>

                        <li>
                            <a href="{{route('projects.globus.downloads.index', [$project])}}">Globus download files</a>
                        </li>

                        <li>
                            <a href="https://materials-commons.github.io/materials-commons-cli/html/manual/up_down_globus.html"
                               target="_blank">CLI file transfer documentation</a>
                        </li>

                        <li>
                            <a href="https://materials-commons.github.io/materials-commons-api/html/manual/file_upload_download.html"
                               target="_blank">API file transfer documentation</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><strong>Experiments</strong></h5>
                <hr/>
                <p class="card-text">
                    In Materials Commons a set of related data and metadata is called an experiment. This is the case
                    whether it comes from materials or computer lab. An experiment can be
                    constructed by importing a lightly annotated spreadsheet (the most common way) or
                    using the Materials Commons API.
                </p>
                <p>
                    Experiments record the experimental or computational processes performed, parameters used,
                    properties measured or results calculated, and link to the files used or created. This helps
                    all collaborators to understand what was done and to explore, query, and use project results.
                </p>
                <div class="card-text">
                    <ul class="list-unstyled ml-3">
                        <li><a href="{{route('projects.experiments.index', [$project])}}">View project experiments</a>
                        </li>
                        <li><a href="https://materialscommons.org/docs/docs/reference/spreadsheets/#overview"
                               target="_blank">Spreadsheet annotation documentation</a></li>
                        {{--                        <li><a href="#">Example annotated spreadsheets</a></li>--}}
                        <li><a href="{{route('projects.experiments.create', [$project])}}">Create an experiment from an
                                annotated spreadsheet</a></li>
                        {{--                        <li><a href="#">CLI experiments documentation</a></li>--}}
                        <li>
                            <a href="https://materials-commons.github.io/materials-commons-api/html/manual/experiments.html"
                               target="_blank">API experiments documentation</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><strong>Datasets and Communities</strong></h5>
                <hr/>
                <p class="card-text">
                    Files, data, and metadata can be selected from one or more project experiments to be
                    published as a dataset. Datasets are made publicly available for query and exploration.
                    They may be cited and linked to using a DOI, and dataset files may be downloaded as a zip file
                    or using Globus. Datasets may be imported into a new or existing project for re-use.
                </p>
                <div class="card-text">
                    <ul class="list-unstyled ml-3">
                        <li><a href="{{route('projects.datasets.index', [$project])}}">View project datasets</a></li>
                        <li><a href="{{route('projects.datasets.create', [$project])}}">Create a dataset</a></li>
                        <li><a href="{{route('public.index')}}">Goto published datasets</a></li>
                        {{--                        <li><a href="#">Import a published dataset</a></li>--}}
                        {{--                        <li><a href="{{route('')}}">CLI dataset documentation</a></li>--}}
                        <li>
                            <a href="https://materialscommons.org/docs/docs/publishing-data/create-dataset-website/"
                               target="_blank">How to publish your research</a>
                        </li>
                        <li>
                            <a href="https://materials-commons.github.io/materials-commons-api/html/manual/datasets.html"
                               target="_blank">API dataset documentation</a>
                        </li>
                    </ul>
                </div>
                <div class="card-text">
                    <p class="card-text">
                        Related published datasets can be added to a community to improve findability and provide
                        a template for similar work.
                    </p>
                </div>
                <div class="card-text">
                    <ul class="list-unstyled ml-3">
                        <li><a href="{{route('communities.index')}}">View communities</a></li>
                        <li><a href="{{route('communities.create')}}">Create a new community</a></li>
                        <li><a href="https://materialscommons.org/docs/docs/communities/" target="_blank">Communities
                                documentation</a></li>
                        {{--                        <li><a href="#">CLI community documentation</a></li>--}}
                        {{--                        <li><a href="#">API community documentation</a></li>--}}
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<x-display-markdown-file :file="$readme"></x-display-markdown-file>
