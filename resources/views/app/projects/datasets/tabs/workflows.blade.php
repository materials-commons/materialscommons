@component('components.card')
    @slot('header')
        Workflows
    @endslot

    @slot('body')
        @include('partials.workflows.index', ['workflows' => $workflows])
    @endslot
@endcomponent