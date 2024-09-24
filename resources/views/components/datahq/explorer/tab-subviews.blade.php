<nav class="nav nav-pills mb-3">
    @foreach($subviews as $subview)
        <a class="nav-link no-underline rounded-pill {{setActiveNavByParam('subview', $subview->key)}}"
           href="#">{{$subview->name}}</a>
    @endforeach
</nav>