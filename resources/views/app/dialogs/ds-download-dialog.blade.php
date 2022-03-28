<div class="modal fade" tabindex="-1" id="ds-download-dialog" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title">Download Dataset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px">
                <h4>Optional details</h4>
                <a href="#">Skip and download</a>
                <p>If you'd take a moment we'd like to get a few details about you.</p>
                <form method="post" action="" id="download-details">
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
                        <p>You can create an account...</p>
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
        function loginAndDownload() {
            let loginAndDownloadRoute = "";
            $('#download-details').attr('action', loginAndDownloadRoute);
            document.getElementById('download-details').submit();
        }
    </script>
@endpush