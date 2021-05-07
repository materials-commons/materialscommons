<div>
    <h5>
        This is context sensitive example code. It will change depending on the page your are on.
    </h5>
    <h4>CLI Code</h4>
    <p>
        The documentation for the Materials Commons CLI can be found
        <a href="https://materials-commons.github.io/materials-commons-cli/html/index.html" target="_blank">
            here
        </a>.
    </p>
    @if (Request::routeIs('projects.files*'))
        <p>The documentation for interacting with project files from the command line can be found
            <a href="https://materials-commons.github.io/materials-commons-cli/html/manual/ls_mkdir_rm_mv.html"
               target="_blank">
                here
            </a>.
        </p>
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
        <p>The documentation for interacting with project files from the command line can be found
            <a href="https://materials-commons.github.io/materials-commons-cli/html/manual/ls_mkdir_rm_mv.html"
               target="_blank">
                here
            </a>.
        </p>
    @endif
    <pre>
# Setup CLI if you haven't already done so.
mc remote --add {{auth()->user()->email}} https://materialscommons.org/api

# Many of the examples for the CLI assume your current working directory is the root of the cloned project
# For example if you did an 'mc clone' while in ~/myprojects for a project named MyProj, then the examples
# for things like 'mc ls .' assume you are in ~/myprojects/MyProj
    </pre>
    @if (Request::routeIs('projects.files*'))
        @include('partials.help.api-cli._file-up-down-cli-help')
    @elseif (Request::routeIs('projects.folders.upload'))
        @include('partials.help.api-cli._file-up-down-cli-help')
    @elseif(Request::routeIs('projects.folders*'))
        @include('partials.help.api-cli._dir-show-cli-help')
    @elseif (Request::routeIs('projects.experiments*'))
        @include('partials.help.api-cli._exp-show-cli-help')
    @elseif (Request::routeIs('projects.datasets.index'))
        @include('partials.help.api-cli._dataset-cli-help')
    @elseif (Request::routeIs('projects.datasets*'))
        @include('partials.help.api-cli._dataset-cli-help')
    @elseif(Request::routeIs('projects.show'))
        @include('partials.help.api-cli._project-cli-help')
    @elseif (Request::routeIs('dashboard.projects*'))
        @include('partials.help.api-cli._project-cli-help')
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
    {{--# Alternatively you can create a client by passing in your email and password:--}}
    {{--c = mcapi.login("{{auth()->user()->email}}", "Your Password Here")--}}

    {{--# One method you can use so that your scripts don't contain your API Token or username/password is to store your--}}
    {{--# your API Token in an environment variable. For example, on a Linux or Mac system, if you are using bash as your--}}
    {{--# shell you can add the following line to your ~/.bashrc:--}}
    {{--# export MCAPI_TOKEN="{{auth()->user()->api_token}}"--}}
    {{--#--}}
    {{--# Then you can access it as follows:--}}

    {{--import os--}}
    {{--c = mcapi.Client(os.getenv("MCAPI_TOKEN"))--}}
    <pre>
import materials_commons.api as mcapi

# Create Client: The token shown is your API token. You can cut and paste this code in to initialize the client.
c = mcapi.Client("{{auth()->user()->api_token}}")
    </pre>
    @if (Request::routeIs('projects.files*'))
        @include('partials.help.api-cli._file-show-api-help')
    @elseif (Request::routeIs('projects.folders.upload'))
        @include('partials.help.api-cli._file-up-down-api-help')
    @elseif(Request::routeIs('projects.folders*'))
        @include('partials.help.api-cli._dir-show-api-help')
    @elseif (Request::routeIs('projects.experiments.index'))
        @include('partials.help.api-cli._exp-list-api-help')
    @elseif (Request::routeIs('projects.experiments*'))
        @include('partials.help.api-cli._exp-show-api-help')
    @elseif (Request::routeIs('projects.datasets.index'))
        @include('partials.help.api-cli._dataset-list-api-help')
    @elseif (Request::routeIs('projects.datasets*'))
        @include('partials.help.api-cli._dataset-show-api-help')
    @elseif(Request::routeIs('projects.show'))
        @include('partials.help.api-cli._project-show-api-help')
    @elseif (Request::routeIs('dashboard.projects*'))
        @include('partials.help.api-cli._project-list-api-help')
    @endif
</div>