<div class="row">
    <div class="col">
        <div class="float-end">
            @auth
                <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
                   title="Publish data"
                   class="btn btn-success float-endx">
                    <i class="fas fa-plus mr-2"></i> Create Your Dataset
                </a>
            @else
                <a href="{{route('login-for-upload')}}" title="Publish data"
                   class="btn btn-success float-endx">
                    <i class="fas fa-plus mr-2"></i> Create Your Dataset
                </a>
            @endauth
        </div>
    </div>
</div>
{{--<br>--}}
