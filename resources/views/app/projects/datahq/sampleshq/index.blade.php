<x-layouts.project :project="$project">
    <x-slot:pageTitle>
        "{{$project->name}} - Data Explorer"
    </x-slot:pageTitle>

    <x-slot:content>
        <x-card>
            <x-slot:header>
                Data Explorer
                <x-datahq.header-controls :project="$project"/>
            </x-slot:header>

            <x-slot:body>
                <div>
                    <x-datahq.explorer.tabs :project="$project"/>
                    <br/>
                    <div>
                        <x-datahq.sampleshq.tab-view-handler :project="$project"/>
                    </div>
                </div>
            </x-slot:body>
        </x-card>
    </x-slot:content>
</x-layouts.project>
