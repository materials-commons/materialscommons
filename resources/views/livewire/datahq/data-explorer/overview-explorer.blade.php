<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $view == 'samples'])
            href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'samples', 'context' => $context])}}">
                Samples
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $view == 'computations'])
            href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'computations', 'context' => $context])}}">
                Computations
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $view == 'processes'])
            href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'processes', 'context' => $context])}}">
                Processes
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $view == 'sampleattrs'])
            href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'sampleattrs', 'context' => $context])}}">
                Sample Attributes
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $view == 'computationattrs'])
            href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'computationattrs', 'context' => $context])}}">
                Computation Attributes
            </a>
        </li>

        <li class="nav-item">
            <a wire:navigate @class(["nav-link", "no-underline", "active" => $view == 'processattrs'])
            href="{{route('projects.datahq.index', [$project, 'explorer' => 'overview', 'view' => 'processattrs', 'context' => $context])}}">
                Process Attributes
            </a>
        </li>

    </ul>
    <div class="mt-2">
        @if($view == 'samples')
            <livewire:projects.entities.entities-table :project="$project" :experiment="$experiment"/>
        @elseif($view == 'computations')
            <x-projects.computations.computations-table :project="$project"/>
        @elseif($view == 'processes')
            <x-projects.processes.processes-table :project="$project"/>
        @elseif($view == 'sampleattrs')
            <x-projects.samples.sample-attributes-table :project="$project"/>
        @elseif($view == 'computationattrs')
            <x-projects.computations.computation-attributes-table :project="$project"/>
        @elseif($view == 'processattrs')
            <x-projects.processes.process-attributes-table :project="$project"/>
        @endif
    </div>
</div>