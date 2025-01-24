@props(['summary', 'overview', 'showOverview' => false])
<x-card class="bg-light">
    <x-slot:body>
        {{$summary}}
        @if(isset($overview))
            @if($showOverview)
                {{$overview}}
            @endif
        @endif
    </x-slot:body>
</x-card>