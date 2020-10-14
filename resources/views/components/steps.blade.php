<ul class="steps">
    @foreach($steps as $step)
        @if(isset($step['success']))
        @elseif (isset($step['error']))
        @elseif(isset($step['active']))
        @else
        @endif
    @endforeach
</ul>