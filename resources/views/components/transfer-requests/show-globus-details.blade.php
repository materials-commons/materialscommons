<div class="row">
    <div class="card bg-light col-12">
        <div class="card-body">
            <h5 class="card-title">Globus Details</h5>
            <div class="mb-2">
                <span>ID: {{$gr->id}}</span>
                <span class="ml-3">UUID: {{$gr->uuid}}</span>
            </div>
            <div class="mb-2">
                <span>State: {{$gr->state}}</span>
            </div>
            <span>Globus Endpoint: <a href="https://app.globus.org/file-manager/collections/{{$gr->globus_endpoint_id}}"
                                      target="_blank">{{$gr->globus_endpoint_id}}</a></span>
            <span class="ml-3">Globus Path: {{$gr->globus_path}}</span>

            <div class="mt-2">
                <span>ACL ID: {{$gr->globus_acl_id}}</span>
            </div>
            <div class="mt-2">
                <span>Identity ID: {{$gr->globus_identity_id}}</span>
            </div>
            <div class="mt-2">
                Globus Transfer URL: <a href="{{$gr->globus_url}}" target="_blank">{{$gr->globus_url}}</a>
            </div>
            <div class="mt-3">
                <a class="" href="https://app.globus.org/file-manager/collections/{{$gr->globus_endpoint_id}}/sharing"
                   target="_blank">Endpoint Permissions</a>
                <a class="ml-3" href="#">Delete ACL</a>
                <a class="ml-3" href="#">Close Transfer</a>
            </div>
        </div>
    </div>
</div>
