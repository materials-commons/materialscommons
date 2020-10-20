@auth
    <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
       title="Publish data"
       class="action-link float-right">
        <i class="fas fa-plus mr-2"></i> Upload and Publish Data
    </a>
@else
    <a href="{{route('login-for-upload')}}" title="Publish data"
       class="action-link float-right">
        <i class="fas fa-plus mr-2"></i> Upload and Publish Data
    </a>
@endauth