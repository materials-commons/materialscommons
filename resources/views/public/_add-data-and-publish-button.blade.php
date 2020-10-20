<div class="row">
    <div class="col">
        <div class="float-right">
            @auth
                <a href="{{route('public.publish.wizard.choose_create_or_select_project')}}"
                   title="Publish data"
                   class="btn btn-success float-rightx">
                    <i class="fas fa-plus"></i> Upload and Publish Data
                </a>
            @else
                <a href="{{route('login-for-upload')}}" title="Publish data"
                   class="btn btn-success float-rightx">
                    <i class="fas fa-plus"></i> Upload and Publish Data
                </a>
            @endauth
        </div>
    </div>
</div>
{{--<br>--}}
