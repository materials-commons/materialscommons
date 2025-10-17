<div class="mt-2">
    <h5>
        <a href="{{route('projects.activities.show', [$project, $activity])}}">{{$activity->name}}</a>
    </h5>
    @if(!blank($activity->description))
        <form>
            <div class="form-group">
                <textarea class="form-control" readonly>{{$activity->description}}</textarea>
            </div>
        </form>
    @endif
    
    <h6>Attributes</h6>
    <dl class="row ms-2">
        @foreach($activity->attributes->sortBy('name') as $attribute)
            @php
                $isUnique = false;
                $isDifferent = false;
                
                if (isset($side) && $side === 'left' && isset($activity1OnlyAttributes) && $activity1OnlyAttributes->contains($attribute->name)) {
                    $isUnique = true;
                }
                
                if (isset($side) && $side === 'right' && isset($activity2OnlyAttributes) && $activity2OnlyAttributes->contains($attribute->name)) {
                    $isUnique = true;
                }

                if (isset($side) && $side === 'left' && isset($differentValueAttributes) && $differentValueAttributes->contains($attribute->name)) {
                    $isDifferent = true;
                }
                
                if (isset($side) && $side === 'right' && isset($differentValueAttributes) && $differentValueAttributes->contains($attribute->name)) {
                    $isDifferent = true;
                }

                $highlightClass = '';
                if ($isUnique) {
                    $highlightClass = 'bg-primary text-white';
                } elseif ($isDifferent) {
                    $highlightClass = 'bg-warning';
                }
            @endphp
            
            <dt class="col-7 {{$highlightClass}}">{{$attribute->name}}:</dt>
            <dd class="col-4 {{$highlightClass}}">
                @if(is_array($attribute->values[0]->val["value"]))
                    @json($attribute->values[0]->val["value"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                @else
                    @if(blank($attribute->values[0]->val["value"]))
                        No value
                    @else
                        {{$attribute->values[0]->val["value"]}}
                    @endif
                @endif
                @if($attribute->values[0]->unit != "")
                    {{$attribute->values[0]->unit}}
                @endif
            </dd>
        @endforeach
    </dl>
    
    <h6>Measurements</h6>
    @include('partials.activities._activity-measurements', ['activity' => $activity])
    
    <h6>Files</h6>
    @include('partials.activities._activity-files', ['activity' => $activity])
</div>