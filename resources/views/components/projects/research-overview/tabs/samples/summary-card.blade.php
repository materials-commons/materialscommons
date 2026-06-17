@props([
    'label',
    'value',
    'hint' => '',
    'color' => 'secondary',
])

<x-projects.research-overview.summary-card
    :label="$label"
    :value="$value"
    :hint="$hint"
    :color="$color"
/>
