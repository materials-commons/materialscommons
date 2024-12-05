<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $tab == 'samples'])
            href="{{route('projects.datahq.index', [$project, 'view' => 'overview', 'tab' => 'samples', 'context' => $context])}}">
                Samples
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $tab == 'computations'])
            href="{{route('projects.datahq.index', [$project, 'view' => 'overview', 'tab' => 'computations', 'context' => $context])}}">
                Computations
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $tab == 'processes'])
            href="{{route('projects.datahq.index', [$project, 'view' => 'overview', 'tab' => 'processes', 'context' => $context])}}">
                Processes
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $tab == 'sampleattrs'])
            href="{{route('projects.datahq.index', [$project, 'view' => 'overview', 'tab' => 'sampleattrs', 'context' => $context])}}">
                Sample Attributes
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $tab == 'computationattrs'])
            href="{{route('projects.datahq.index', [$project, 'view' => 'overview', 'tab' => 'computationattrs', 'context' => $context])}}">
                Computation Attributes
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $tab == 'processattrs'])
            href="{{route('projects.datahq.index', [$project, 'view' => 'overview', 'tab' => 'processattrs', 'context' => $context])}}">
                Process Attributes
            </a>
        </li>

    </ul>
    <div class="mt-2">
        @if($tab == 'samples')
            <x-projects.samples.samples-table :project="$project"/>
        @elseif($tab == 'computations')
            <x-projects.computations.computations-table :project="$project"/>
        @elseif($tab == 'processes')
            <x-projects.processes.processes-table :project="$project"/>
        @elseif($tab == 'sampleattrs')
            <x-projects.samples.sample-attributes-table :project="$project"/>
        @elseif($tab == 'computationattrs')
            <x-projects.computations.computation-attributes-table :project="$project"/>
        @elseif($tab == 'processattrs')
            <x-projects.processes.process-attributes-table :project="$project"/>
        @endif
    </div>
</div>