<x-layouts.project :project="$project">
    <x-slot:pageTitle>
        "{{$project->name}} - Samples Data Explorer"
    </x-slot:pageTitle>

    <x-slot:content>
        <x-card>
            <x-slot:header>
                Data Explorer
                <x-datahq.header-controls :project="$project"/>
            </x-slot:header>

            <x-slot:body>
                <x-datahq.sampleshq :project="$project"/>
            </x-slot:body>
        </x-card>
    </x-slot:content>
</x-layouts.project>
