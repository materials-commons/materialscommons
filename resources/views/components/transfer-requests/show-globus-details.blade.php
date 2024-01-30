<div class="row">
    <div class="card bg-light col-12">
        <div class="card-body">
            <h5 class="card-title">Globus Details</h5>
            <span>Globus Endpoint: <a href="https://app.globus.org/file-manager/collections/{{$gr->globus_endpoint_id}}"
                                      target="_blank">{{$gr->globus_endpoint_id}}</a></span>
            <span class="ml-3">Globus Path: {{$gr->globus_path}}</span>
            <span class="ml-3">ACL ID: {{$gr->globus_acl_id}}</span>
            <div>
                <span>Identity ID: {{$gr->globus_identity_id}}</span>
            </div>
            <div>
                Globus Transfer URL: <a href="{{$gr->globus_url}}" target="_blank">{{$gr->globus_url}}</a>
            </div>
            <div>
                Globus Transfer Activity: <a href=""
            </div>
            <div class="mt-3">
                <a class="" href="https://app.globus.org/file-manager/collections/{{$gr->globus_endpoint_id}}/sharing"
                   target="_blank">Endpoint Permissions</a>
            </div>
        </div>
    </div>
</div>