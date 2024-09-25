<div>
        @if($subview == "index")
                <x-projects.samples.samples-table :project="$project"/>
        @else
                <h2>Tab Subview Handler for {{$subview}}</h2>
        @endif
</div>