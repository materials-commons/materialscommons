<div>
    <label>Authors</label>
    <p>Drag and drop to re-order authors</p>
    @include('components.datasets._add_authors_dialog')
    <br>
    <div x-data="initEditAuthor()">
        <div class="modal fade" tabindex="-1" id="edit-author-dialog" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Author</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row mt-2">
                            <div class="col">
                                <input class="form-control" name="author_name" type="text"
                                       placeholder="Name...(Required)"
                                       x-model="author.name" id="author_name" required>
                            </div>
                            <div class="col">
                                <input class="form-control" name="author_email" type="email"
                                       x-model="author.email" placeholder="Email...(Required)" id="author_email"
                                       required>
                            </div>
                            <div class="col">
                                <input class="form-control" name="author_affiliations"
                                       x-model="author.affiliations" type="text" placeholder="Affiliations...(Required)"
                                       x-on:keydown.enter="updateAuthor()"
                                       required id="author_affiliations">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" x-on:click="updateAuthor()">Update Author</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                    </div>
                </div>
            </div>
        </div>

        <table id="authors" class="table table-hover" style="width: 100%">
            <thead>
            <tr>
                <th>Seq.</th>
                <th></th>
                <th>Name</th>
                <th>Affiliations</th>
                <th>Email</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if(!is_null($dataset))
                @foreach($dataset->ds_authors as $author)
                    <tr id="row-{{$loop->index}}">
                        <td>{{$loop->index}}</td>
                        <td><i class="fas fa-fw fa-grip-vertical mr-2"></i></td>
                        <td>{{$author['name']}}</td>
                        <td>{{$author['affiliations']}}</td>
                        <td>{{$author['email']}}</td>
                        <td>
                            <div class="float-right">
                                <a class="action-link" href="#"
                                   x-on:click="openEditDialog({{$loop->index}}, '{{$author['name']}}', '{{$author['affiliations']}}', '{{$author['email']}}')">
                                    <i class="fas fa-fw fa-edit"></i></a>
                                <a class="action-link" href="#" target="_blank"><i class="fas fa-fw fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                @foreach($project->team->members->merge($project->team->admins) as $author)
                    <tr id="row-{{$loop->index}}">
                        <td>{{$loop->index}}</td>
                        <td><i class="fas fa-fw fa-grip-vertical mr-2"></i></td>
                        <td>{{$author->name}}</td>
                        <td>{{$author->affiliations}}</td>
                        <td>{{$author->email}}</td>
                        <td>
                            <div class="float-right">
                                <a class="action-link" href="#"
                                   x-on:click="openEditDialog({{$loop->index}}, '{{$author->name}}', '{{$author->affiliations}}', '{{$author->email}}')">
                                    <i class="fas fa-fw fa-edit"></i></a>
                                <a class="action-link" href="#" target="_blank"><i class="fas fa-fw fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        let authorTable;
        $(document).ready(function () {
            authorTable = $("#authors").DataTable({
                rowReorder: true,
                columnDefs: [
                    {targets: 0, visible: false}
                ],
                fnRowCallback: function (nRow, data) {
                    nRow.children[0].className = "cursor-move";
                    if (nRow.id == "" || nRow.id == null) {
                        nRow.id = `row-${data[0]}`;
                    }
                }
            });
            nextId = authorTable.data().length;
        });

        function initEditAuthor() {
            return {
                author: {
                    id: 0,
                    name: '',
                    affiliations: '',
                    email: '',
                },

                openEditDialog(row, name, affiliations, email) {
                    console.log(` openEditDialog: ${row}, ${name}, ${affiliations}, ${email}`);
                    this.author.id = row;
                    this.author.name = name;
                    this.author.affiliations = affiliations;
                    this.author.email = email;
                    $('#edit-author-dialog').modal();
                },

                updateAuthor() {
                    console.log(authorTable);
                    let row = authorTable.row($(`#row-${this.author.id}`)).data();
                    console.log(`Updating: #row-${this.author.id}, ${this.author.name}, ${this.author.affiliations}, ${this.author.email}`);
                    authorTable.row($(`#row-${this.author.id}`)).data([row[0], row[1], this.author.name, this.author.affiliations, this.author.email, row[5]]);
                    $('#edit-author-dialog').modal('hide');
                }
            };
        }
    </script>
@endpush
