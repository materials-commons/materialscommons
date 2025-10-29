<div class="modal fade" tabindex="-1" id="{{$dialogId}}" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-nav">
                <h5 class="modal-title">Download Dataset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="help-color">&times;</span>
                </button>
            </div>
            <div class="modal-body" stylex="height: 675px">
                <h4>Materials Commons Account Benefits</h4>
                <p>
                    You can optionally create an account on Materials Commons. Creating an account gives you many
                    benefits, such as being able to follow a dataset. Following a dataset will
                    let you know if the dataset has changed.
                </p>
                <div class="mt-4">
                    <a class="btn btn-danger" href="#" data-bs-dismiss="modal">Cancel</a>
                    <a class="btn btn-warning" href="#">Download</a>
                    <a class="btn btn-success" href="{{$createAccountRoute}}">
                        Create Account And Download
                    </a>
                    <a class="btn btn-primary" href="{{$loginRoute}}">
                        Login and Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
