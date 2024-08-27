<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('public.datasets.data-dictionary.entities')}}"
           href="{{route('public.datasets.data-dictionary.entities', [$dataset])}}">
            Sample Attributes
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link no-underline {{setActiveNavByName('public.datasets.data-dictionary.activities')}}"
           href="{{route('public.datasets.data-dictionary.activities', [$dataset])}}">
            Process Attributes
        </a>
    </li>
</ul>