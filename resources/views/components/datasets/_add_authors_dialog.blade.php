<div x-data="initAddAuthors()">
    <a href="#" x-on:click="openDialog()"><i class="fas fa-fw fa-plus"></i>Add Author</a>
    <div class="modal fade" tabindex="-1" id="add-authors-dialog" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Authors</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div x-text="authorAdded"></div>
                    <div class="form-row mt-2">
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
                    <button type="button" class="btn btn-success" x-on:click="addAuthor()"
                            x-bind:disabled="!allFieldsFilled()">Add
                    </button>
                    <button type="button" class="btn btn-success" x-on:click="addAnother()"
                            x-bind:disabled="!allFieldsFilled()">Add Another
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function initAddAuthors() {
                return {
                    author: {
                        name: '',
                        affiliations: '',
                        email: '',
                    },

                    authorAdded: '',

                    openDialog() {
                        this.clearAuthor();
                        this.authorAdded = '';
                        $('#add-authors-dialog').modal();
                        $('#author_name').focus();
                        $('#add-authors-dialog').on('shown.bs.modal', function () {
                            setTimeout(function () {
                                $('#author_name').focus();
                            }, 300);

                        });
                    },

                    allFieldsFilled() {
                        return this.author.name !== '' && this.author.affiliations !== '' && this.author.email !== '';
                    },

                    addAuthor() {
                        this.addAuthorToTable();
                        $('#add-authors-dialog').modal('hide');
                    },

                    addAnother() {
                        this.addAuthorToTable();
                        this.authorAdded = `Added author ${this.author.name}`;
                        $('#author_name').focus();
                        this.clearAuthor();
                    },

                    addAuthorToTable() {
                        let len = authorTable.data().length;
                        authorTable.row.add([
                            len,
                            `<i class="fas fa-fw fa-grip-vertical mr-2"></i>`,
                            this.author.name, this.author.affiliations, this.author.email,
                            `<div class="float-right">
                                <a class="action-link" href="#"><i class="fas fa-fw fa-edit"></i></a>
                                <a class="action-link" href="#"><i class="fas fa-fw fa-trash"></i></a>
                            </div>`
                        ]).draw();
                    },

                    clearAuthor() {
                        this.author.name = '';
                        this.author.affiliations = '';
                        this.author.email = '';
                    },
                };
            }
        </script>
    @endpush
</div>