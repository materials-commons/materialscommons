<div class="modal fade" tabindex="-1" id="ds-download-dialog" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title">Download Dataset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 675px">
                <h4>Create Account</h4>
                <p>
                    You can optionally create an account on Materials Commons. Creating an account gives you many
                    benefits, such as being able to follow a dataset. Following a dataset will
                    let you know if the dataset has changed.
                </p>
                <a href="#">Skip and download</a>
                <a href="{{route('login-for-dataset-zipfile-download', [$dataset])}}" class="btn btn-success">
                    Login to download
                </a>
                <form method="post" action="{{route('login')}}" id="download-details" class="mt-4">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" name="name" value="" type="text"
                               placeholder="Name..." required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" name="email" value="" type="email"
                               placeholder="Email...">
                    </div>
                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>

                        <div>
                            <input id="password" type="password"
                                   class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required>
                    </div>

                    <div class="form-group">
                        <div class="captcha col-md-4">
                            <span>{!! captcha_img('flat') !!}</span>
                            <button type="button" class="btn btn-danger" class="reload" id="reload">
                                &#x21bb;
                            </button>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha..."
                               name="captcha">
                    </div>
                    <div class="float-right">
                        <a class="btn btn-danger" href="#" data-dismiss="modal">Cancel</a>
                        <a class="btn btn-primary" data-dismiss="modal" onclick="loginAndDownload()">
                            Login and Download
                        </a>
                        <a class="btn btn-success" data-dismss="modal"
                           onclick="document.getElementById('download-details').submit()">
                            Create Account And Download
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#reload').click(function () {
            $.ajax({
                type: 'GET',
                url: "{{route('reload-captcha')}}",
                success: function (data) {
                    $('.captcha span').html(data.captcha);
                }
            });
        });

        function loginAndDownload() {
            // let loginAndDownloadRoute = "";
            // $('#download-details').attr('action', loginAndDownloadRoute);
            document.getElementById('download-details').submit();
        }
    </script>
@endpush