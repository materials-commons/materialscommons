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
            <input class="form-control" id="tags" name="tags" value="tags1,tags2,tags3">
        </div>

        <div class="form-group">
            <label for="communities">Communities</label>
            <select class="custom-select">
                <option selected>Select Community</option>
                @foreach($communities as $community)
                    <option wire:key="{{ $community->id }}" value="{{ $community->id }}">
                        {{ $community->name }}
                    </option>
                @endforeach
            </select>
            <a href="#" class="btn btn-primary mt-3">Add Community To Dataset</a>
        </div>


        {{--        <label for="license">Choose A License</label>--}}
        {{--        <div class="form-group">--}}
        {{--            <div class="col-12">--}}
        {{--                @include('livewire.projects.datasets.crud.tabs._no-license')--}}
        {{--            </div>--}}
        {{--            <div class="col-12">--}}
        {{--                @include('livewire.projects.datasets.crud.tabs._odbl-license')--}}
        {{--            </div>--}}
        {{--            <div class="col-12">--}}
        {{--                @include('livewire.projects.datasets.crud.tabs._odc-by-license')--}}
        {{--            </div>--}}
        {{--            <div class="col-12">--}}
        {{--                @include('livewire.projects.datasets.crud.tabs._pddl-license')--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <br/>
        <button type="submit" class="btn btn-primary">
            Save
            <div wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
            </div>
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
        console.log('details navigated');
        let tagsInput = document.querySelector('#tags');
        let tagify = new Tagify(tagsInput, {
            whitelist: ['tag1', 'tag2', 'tag3'],
        });

        tagsInput.addEventListener('change', onChange);

        function onChange(e) {
            @this.
            call('setTags', e.target.value);
        }
    });

</script>
@endscript
