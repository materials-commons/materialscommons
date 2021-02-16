<div>
    @if(is_null($globusRequest))
        There is no active globus transfer
    @else
        {{--        <div class="row">--}}
        {{--            <div class="border rounded col-6">--}}
        {{--                <div class="m-2">--}}
        {{--                    <x-projects.globus.monitor.show-other-active-globus-uploads :globus-request="$globusRequest"/>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--            <div class="border rounded col-6">--}}
        {{--                <div class="m-2">--}}
        {{--                    <x-projects.globus.monitor.show-conflicting-globus-file-uploads :globus-request="$globusRequest"/>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div class="row">
            <x-projects.globus.monitor.show-other-active-globus-uploads :globus-request="$globusRequest"/>
        </div>
        <x-projects.globus.monitor.show-file-upload-status :globus-request="$globusRequest"/>
    @endif
</div>