<div class="modal fade" tabindex="-1" id="edit-author-dialog" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Welcome Aboard!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-2">
                    <div class="col">
                        <input class="form-control" name="author_name" type="text" placeholder="Name...(Required)"
                               x-model="author.name" id="author_name" required>
                    </div>
                    <div class="col">
                        <input class="form-control" name="author_email" type="email"
                               x-model="author.email" placeholder="Email...(Required)" id="author_email" required>
                    </div>
                    <div class="col">
                        <input class="form-control" name="author_affiliations"
                               x-model="author.affiliations" type="text" placeholder="Affiliations...(Required)"
                               x-on:keydown.enter="addAnother()"
                               required id="author_affiliations">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Dismiss</button>
            </div>
        </div>
    </div>
</div>
