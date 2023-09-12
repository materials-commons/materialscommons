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

<p class="col-7">
    Materials Commons projects contain both structured data and unstructured data. Materials Commons
    understands data imported from spreadsheets and contains tools for viewing and querying this data.
</p>

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
                            <a href="#">View project files</a>
                            <small class="row text-muted ml-3">View all files in the project.</small>
                        </li>

                        <li>
                            <a href="#">Website upload</a>
                            <small class="row text-muted ml-3">Upload individual files into your project.</small>
                        </li>

                        <li>
                            <a href="#">Globus file transfer</a>
                            <small class="row text-muted ml-3">Upload/download files using&nbsp;<a
                                        href="https://globus.org" target="_blank">Globus</a>.
                            </small>
                        </li>

                        <li>
                            <a href="#">CLI file transfer documentation</a>
                            <small class="row text-muted ml-3">
                                Documentation on using the Materials Commons CLI to upload/download files.
                            </small>
                        </li>

                        <li>
                            <a href="#">API file transfer documentation</a>
                            <small class="row text-muted ml-3">
                                Documentation on using the Materials Commons API to upload/download files.
                            </small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><strong>Studies</strong></h5>
                <hr/>
                <p class="card-text">
                    In Materials Commons, a set of related data and metadata is called a study,
                    whether it comes from the materials lab or computer lab. A study can be
                    constructed by importing a lightly annotated spreadsheet (the most common way) or
                    using the Materials Commons API.
                </p>
                <p>
                    Studies record the experimental or computational processes performed, parameters used,
                    properties measured or results calculated, and link to the files used or created. This helps
                    all collaborators to understand what was done and to explore, query, and use project results.
                </p>
                <div class="card-text">
                    <ul class="list-unstyled ml-3">
                        <li><a href="#">View project studies</a></li>
                        <li><a href="#">Spreadsheet annotation documentation</a></li>
                        <li><a href="#">Example annotated spreadsheets</a></li>
                        <li><a href="#">Create a study from an annotated spreadsheet</a></li>
                        <li><a href="#">CLI study documentation</a></li>
                        <li><a href="#">API study documentation</a></li>
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
                        <li><a href="#">View project datasets</a></li>
                        <li><a href="#">Create a dataset</a></li>
                        <li><a href="#">Find published datasets</a></li>
                        <li><a href="#">Import a published dataset</a></li>
                        <li><a href="#">CLI dataset documentation</a></li>
                        <li><a href="#">API dataset documentation</a></li>
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
                        <li><a href="#">View communities</a></li>
                        <li><a href="#">Create a new community</a></li>
                        <li><a href="#">CLI community documentation</a></li>
                        <li><a href="#">API community documentation</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>