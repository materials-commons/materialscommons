<div {{ $attributes->merge(['class' => 'p-2 rounded mc-md']) }}>
    {!! $toHtml($slot) !!}
</div>