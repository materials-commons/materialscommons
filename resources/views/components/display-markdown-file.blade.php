<div>
    @if (!is_null($file))
        <br/>
        <br/>
        <x-card>
            <x-slot name="header">{{$file->name}}</x-slot>
            <x-slot name="body">
                <x-markdown class="mc-md" flavor="github">{!!$contents!!}</x-markdown>
            </x-slot>
        </x-card>
    @endif
</div>