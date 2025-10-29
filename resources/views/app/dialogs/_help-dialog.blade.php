<div class="modal fade" tabindex="-1" id="help-dialog" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title help-color">Materials Commons Help</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <p class="font-weight-bold">Questions, suggestions or need help? You can
                        <a href="mailto:materials-commons-help@umich.edu">email</a> us!
                    </p>
                </div>
                @include('layouts.navs._app-documentation')
            </div>
            <div class="modal-footer">
                <a class="btn btn-info" data-bs-toggle="modal" data-bs-dismiss="modal" href="#welcome-dialog">
                    Welcome Dialog!
                </a>
                <a class="btn btn-secondary" href="/mcdocs2" target="_blank">Goto Docs</a>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
            </div>

            @push('scripts')
                <script>
                    $(document).ready(function () {
                        if ('showDirectoryPicker' in window) {
                            console.log('showDirectoryPicker is supported');
                        } else {
                            console.log('showDirectoryPicker is not supported');
                        }

                        {{--$('#start-tour').on('click', function () {--}}
                        {{--    window.tourService.initState("{{auth()->user()->api_token}}");--}}

                        {{--    // Get current route--}}
                        {{--    const currentRoute = "{{Route::currentRouteName()}}";--}}

                        {{--    // Get the appropriate tour for the current route--}}
                        {{--    const tourName = window.tourService.getTourForRoute(currentRoute);--}}

                        {{--    if (tourName) {--}}
                        {{--        // Start the tour--}}
                        {{--        window.tourService.startTour(tourName);--}}
                        {{--    } else {--}}
                        {{--        $('#no-tour-dialog').modal('show');--}}
                        {{--        console.error('No tour available for this page');--}}
                        {{--    }--}}
                        {{--});--}}
                    });
                </script>
            @endpush
        </div>
    </div>
</div>
