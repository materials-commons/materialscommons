<div>
    <form wire:submit="save">
        <div class="form-group required">
            <label for="title" class="rl">Title</label>
            <input wire:model.blur="form.name" type="text" class="form-control" id="title" placeholder="Title...">
            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group required">
            <label for="description" class="rl">Description</label>
            <textarea wire:model.blur="form.description" class="form-control"
                      id="description" placeholder="Description..."></textarea>
            @error('form.description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group required">
            <label for="summary" class="rl">Summary</label>
            <input wire:model.blur="form.summary" type="text" class="form-control" id="summary"
                   placeholder="Summary...">
            @error('form.summary') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div wire:ignore class="form-group">
            <label for="tags">Tags</label>
            <input class="form-control" id="tags" name="tags" value="{{$this->getTagsAsString()}}">
        </div>

        <div class="form-group">
            <label for="communities">Communities</label>
            <select wire:model="communityId" class="custom-select">
                <option selected>Select Community</option>
                @foreach($communities as $community)
                    <option wire:key="{{ $community->id }}" value="{{ $community->id }}">
                        {{ $community->name }}
                    </option>
                @endforeach
            </select>
            <a href="#" wire:click="addCommunity" class="btn btn-primary mt-3">Add Community To Dataset</a>
        </div>
        <ul>
            @foreach($this->form->dataset->communities as $community)
                <li>{{$community->name}} <a href="#" wire:click="deleteCommunity({{$community->id}})"
                                            class="action-link"><i
                                class="fa fa-fs fa-trash" style="font-size:12px"></i></a>
                </li>
            @endforeach
        </ul>

        <br/>
        <livewire:projects.datasets.crud.update-license :license="$this->form->dataset->license"/>
        <br/>

        <button type="submit" class="btn btn-primary">
            Save
        </button>
        <button type="button" class="btn btn-primary" wire:click="done">
            Done
        </button>
    </form>

    <div x-show="$wire.showSuccess"
         x-transition.out.opacity.duration.2000ms
         x-effect="if($wire.showSuccess) setTimeout(() => $wire.showSuccess = false, 2000)">
        <div class="text-green-300">
            <i class="fas fa-fw fa-check-circle"></i>Dataset updated successfully
        </div>
    </div>
</div>

@script
<script>
    document.addEventListener('livewire:navigated', () => {
        let tagsInput = document.querySelector('#tags');
        new Tagify(tagsInput);

        tagsInput.addEventListener('change', onChange);

        function onChange(e) {
            $wire.setTags(e.target.value);
        }
    });

</script>
@endscript
