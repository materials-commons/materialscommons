@component('components.card')
    @slot('header')
        Samples
    @endslot

    @slot('body')
        @include('partials.entities._entities-with-used-activities-table')
    @endslot
@endcomponent