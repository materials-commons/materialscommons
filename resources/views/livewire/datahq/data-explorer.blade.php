<div>
    <x-card>
        <x-slot:header>
            Data Explorer
            <livewire:datahq.data-explorer.header-controls :project="$project"/>
        </x-slot:header>

        <x-slot:body>
            <livewire:datahq.data-explorer.overview-explorer :project="$project" :instance="$instance" :tab="$tab"/>
        </x-slot:body>
    </x-card>
</div>
