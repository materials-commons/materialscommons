@component('components.card')
    @slot('header')
        Overview
    @endslot

    @slot('body')
        @include('partials.overview._overview')
    @endslot
@endcomponent