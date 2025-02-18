<div>
    <span class="fs-9" style="font-weight: 300">Drag and Drop to reorder authors...</span>
    <button onclick="$('#authors-modal').modal('show')"
            class="btn btn-success float-right">
        <i class="fa fa-fw fa-plus"></i>Add Author
    </button>
    <div x-sort="$wire.move($item, $position)" class="col-4 mt-4">
        @foreach ($dataset->ds_authors as $author)
            <div x-sort:item="'{{$author['email']}}'" class="card mb-3" wire:key="{{ $author['email'] }}">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fa fa-fw fa-grip-vertical"></i>{{$author['name']}}
                        <a href="#" wire:click="remove('{{$author['email']}}')" class="float-right" style="color: rgb(242, 242, 242)">
                            <i class="fa fa-fw fa-trash"></i>
                        </a>
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Affiliations: {{$author['affiliation']}}</p>
                    <p class="card-text">Email: {{$author['email']}}</p>
                </div>
            </div>
        @endforeach
    </div>
    <x-modal id="authors-modal" title="Add Author">
        <x-slot:body>
            <form wire:submit="addAuthor" id="add-author">
                <div class="form-group required">
                    <label for="name" class="rl">Name</label>
                    <input wire:model="form.name" type="text" class="form-control" id="name" placeholder="Name..">
                    @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group required">
                    <label for="email" class="rl">Email</label>
                    <input wire:model="form.email" type="email" class="form-control" id="email" placeholder="Email..">
                    @error('form.email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group required">
                    <label for="affiliations" class="rl">Affiliations</label>
                    <input wire:model="form.affiliation" type="email" class="form-control" id="affiliations"
                           placeholder="Affilations..">
                    @error('form.affiliation') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </form>
        </x-slot:body>
        <x-slot:footer>
            <button type="button" class="btn btn-primary" wire:click="addAuthor" data-dismiss="modal">Add Author
            </button>
        </x-slot:footer>
    </x-modal>
</div>
