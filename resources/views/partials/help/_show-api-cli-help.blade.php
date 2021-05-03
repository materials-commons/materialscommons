<div>
    <h4>CLI Code</h4>
    <p>
        The documentation for the Materials Commons CLI can be found
        <a href="https://materials-commons.github.io/materials-commons-cli/html/index.html" target="_blank">
            here
        </a>.
    </p>
    @if (Request::routeIs('projects.files*'))
        show api/cli for files
    @elseif (Request::routeIs('projects.show'))
        <p>The documentation for interacting with projects from the command line can be found
            <a href="https://materials-commons.github.io/materials-commons-cli/html/manual/proj_init_clone.html"
               target="_blank">
                here
            </a>.
        </p>
    @elseif (Request::routeIs('projects.folders.upload'))
        <p>The documentation for uploading/downloading files from the command line can be found
            <a href="https://materials-commons.github.io/materials-commons-cli/html/manual/up_down_globus.html"
               target="_blank">
                here
            </a>.
        </p>
    @elseif(Request::routeIs('projects.folders*'))
        show api/cli for folders
    @elseif (Request::routeIs('projects.experiments*'))
        show api/cli for experiments
    @elseif (Request::routeIs('projects.datasets.index'))
        show api/cli for datasets index
    @elseif (Request::routeIs('projects.datasets*'))
        show api/cli for specific dataset
    @elseif(Request::routeIs('projects.show'))
        show api/cli for specific project
    @elseif (Request::routeIs('dashboard.projects*'))
        show api/cli for project index
    @endif
    <pre>
# Setup CLI if you haven't already done so.
mc remote --add {{auth()->user()->email}} https://materialscommons.org/api
    </pre>
    @if (Request::routeIs('projects.files*'))
        show api/cli for files
    @elseif (Request::routeIs('projects.show'))
        @include('partials.help.api-cli._project-cli-help')
    @elseif (Request::routeIs('projects.folders.upload'))
        @include('partials.help.api-cli._file-up-down-cli-help')
    @elseif(Request::routeIs('projects.folders*'))
        show api/cli for folders
    @elseif (Request::routeIs('projects.experiments*'))
        show api/cli for experiments
    @elseif (Request::routeIs('projects.datasets.index'))
        show api/cli for datasets index
    @elseif (Request::routeIs('projects.datasets*'))
        show api/cli for specific dataset
    @elseif(Request::routeIs('projects.show'))
        show api/cli for specific project
    @elseif (Request::routeIs('dashboard.projects*'))
        show api/cli for project index
    @endif
    <hr>
    <h4>API Code</h4>
    <p>
        The documentation for the Python API can be found
        <a href="https://materials-commons.github.io/materials-commons-api/html/index.html" target="_blank">
            here
        </a>.
    </p>
    <br/>
    <pre>
import materials_commons.api as mcapi
# Create Client
c = mcapi.Client("{{auth()->user()->api_token}}", base_url="https://materialscommons.org/api")
    </pre>
    @if (Request::routeIs('projects.files*'))
        show api/cli for files
    @elseif (Request::routeIs('projects.show'))
        @include('partials.help.api-cli._project-api-help')
    @elseif (Request::routeIs('projects.folders.upload'))
        @include('partials.help.api-cli._file-up-down-api-help')
    @elseif(Request::routeIs('projects.folders*'))
        show api/cli for folders
    @elseif (Request::routeIs('projects.experiments*'))
        show api/cli for experiments
    @elseif (Request::routeIs('projects.datasets.index'))
        show api/cli for datasets index
    @elseif (Request::routeIs('projects.datasets*'))
        show api/cli for specific dataset
    @elseif(Request::routeIs('projects.show'))
        show api/cli for specific project
    @elseif (Request::routeIs('dashboard.projects*'))
        show api/cli for project index
    @endif
</div>