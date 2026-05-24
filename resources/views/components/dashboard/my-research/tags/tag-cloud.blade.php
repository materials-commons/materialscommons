@props([
    'title',
    'icon' => 'fas fa-tags',
    'color' => 'success',
    'tags' => collect(),
    'emptyMessage' => 'No tags found.',
])

@php
    $tags = collect($tags);
    $maxCount = max($tags->max('count') ?? 1, 1);
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 background-white">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="card-title text-muted mb-0">
                <i class="{{ $icon }} me-1"></i>{{ $title }}
            </h6>
            <span class="badge text-bg-{{ $color }}">{{ number_format($tags->count()) }}</span>
        </div>

        @if($tags->isEmpty())
            <p class="text-muted mb-0">{{ $emptyMessage }}</p>
        @else
            <div class="d-flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    @php
                        $weight = .78 + (($tag['count'] / $maxCount) * .28);
                    @endphp

                    <a href="{{ route('public.tags.search', ['tag' => $tag['tag']]) }}"
                       class="badge text-bg-{{ $color }} text-decoration-none px-2 py-1"
                       style="font-size:{{ $weight }}rem; font-weight:normal;">
                        {{ $tag['tag'] }}
                        <span class="badge text-bg-light text-dark ms-1" style="font-size:.68rem;">
                            {{ $tag['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
