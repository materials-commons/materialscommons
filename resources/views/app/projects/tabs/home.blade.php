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
                <h5 class="card-title"><strong>Experiments</strong></h5>
                <hr/>
                <p class="card-text">
                    <smallx class="text-mutedx">An experiment is a place to load your metadata and associate your
                        files for a study. You may publish all or some of the data in an experiment.
                    </smallx>
                </p>
                <div class="card-text">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">Create Experiment</a>
                            <small class="row text-muted ml-3">Creates an experiment in the project</small>
                        </li>
                        <li>
                            <a href="#">Create Experiment From Spreadsheet</a>
                            <small class="row text-muted ml-3">Create an experiment and load sample, process and
                                attribute data into it</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><strong>Samples</strong></h5>
                <p class="card-text">
                    A sample is a "thing" you are tracking. It can be a real
                    item such as bar you are going to do a tensile test, or something more abstract such
                    as crystal structure represented in code.
                </p>
                <div class="card-text">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">View Samples</a>
                            <small class="row text-muted ml-3">View all samples in a project. Shows a table of the
                                samples and processes it is associated with.</small>

                        </li>
                        <li>
                            <a href="#">Query Samples</a>
                            <small class="row text-muted ml-3">Query sample data by attributes and values. For example,
                                you can construct a query to view all samples that have a hardness value < 4.</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><strong>Processes</strong></h5>
                <p class="card-text">
                    A process is a step, action or activity. A sample may
                    be associated with multiple processes. For example a sample may have been annealed,
                    then studied on an SEM. A process doesn't have to have a sample associated with it. You
                    could track a computational job run, that produces files you will further analyze.
                </p>
                <div class="card-text">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">View Processes</a>
                            <small class="row text-muted ml-3">View all processes in a project.</small>
                        </li>
                        <li>
                            <a href="#">Query Processes</a>
                            <small class="row text-muted ml-3">Query processes in a project.</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><strong>Datasets</strong></h5>
                <p class="card-text">
                    A dataset allows you to group data together. It can group data across experiments. You can
                    publish a dataset. A dataset is composed of files and optionally samples, processes and attributes.
                    A
                    dataset will have descriptive information including a description, authors, and other optional items
                    such as papers, tags, etc...
                </p>
                <div class="card-text">
                    <ul class="list-unstyled">
                        <li>
                            <a href="#">Create Dataset</a>
                            <small class="row text-muted ml-3">Creates an unpublished dataset.</small>
                        </li>
                        <li>
                            <a href="#">Publish Dataset</a>
                            <small class="row text-muted ml-3">Publishes an existing dataset.</small>
                        </li>
                        <li>
                            <a href="#">View Datasets</a>
                            <small class="row text-muted ml-3">Views an existing dataset.</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>