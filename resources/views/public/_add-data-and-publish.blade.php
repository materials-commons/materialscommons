@auth
    <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
       title="Publish data"
       class="action-link float-end">
        <i class="fas fa-plus mr-2"></i> Create Your Dataset
    </a>
@else
    <a href="{{route('login-for-upload')}}" title="Publish data"
       class="action-link float-end">
        <i class="fas fa-plus mr-2"></i> Create Your Dataset
    </a>
@endauth