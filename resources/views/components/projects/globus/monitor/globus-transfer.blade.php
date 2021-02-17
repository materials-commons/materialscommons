<div>
    @if(is_null($globusRequest))
        There is no active globus transfer
    @else
        <div class="row mb-3">
                <x-projects.globus.monitor.show-other-active-globus-uploads :globus-request="$globusRequest"/>
        </div>
                <hr>
                <x-projects.globus.monitor.show-file-upload-status :globus-request="$globusRequest"/>
        @endif
</div>