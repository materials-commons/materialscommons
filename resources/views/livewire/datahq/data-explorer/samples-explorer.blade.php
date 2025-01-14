<div>
    Samples Explorer Component
    @if(!is_null($project))
        Project: {{$project->id}}
    @endif

    @if(!is_null($experiment))
        Experiment: {{$experiment->id}}
    @endif
    {{--    <ul class="nav nav-tabs">--}}
    {{--        @foreach($tabs as $tab)--}}
    {{--            <li class="nav-item">--}}
    {{--                <a wire:navigate @class(["nav-link", "no-underline", "active" => $tab == $activeTab])--}}
    {{--                href="{{route('projects.datahq.index', [$project, 'view' => 'samples-explorer', 'tab' => $tab, 'subview' => $subview, 'context' => $context])}}">--}}
    {{--                    Samples--}}
    {{--                </a>--}}
    {{--            </li>--}}
    {{--        @endforeach--}}
    {{--    </ul>--}}
    {{--    <div class="mt-2">--}}
    {{--        <livewire:datahq.data-explorer.samples-explorer.active-tab :tab="$activeTab"/>--}}
    {{--    </div>--}}
</div>
