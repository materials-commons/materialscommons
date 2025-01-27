<div>
    <form>
        <div class="form-group required">
            <label for="title" class="rl">Title</label>
            <input type="text" class="form-control" id="title" placeholder="Title...">
        </div>

        <div class="form-group required">
            <label for="description" class="rl">Description</label>
            <textarea class="form-control" id="description" placeholder="Description..."></textarea>
        </div>

        <div class="form-group required">
            <label for="summary" class="rl">Summary</label>
            <input type="text" class="form-control" id="summary" placeholder="Summary...">
        </div>

        {{--        <div class="form-group required">--}}
        {{--            <label class="rl">Authors</label>--}}
        {{--            <ul x-sort>--}}
        {{--                <li x-sort:item>ABC</li>--}}
        {{--                <li x-sort:item>DEF</li>--}}
        {{--                <li x-sort:item>GHI</li>--}}
        {{--            </ul>--}}
        {{--        </div>--}}

        <div class="form-group">
            <label for="tags">Tags</label>
            <input class="form-control" id="tags" name="tags" value="tag1, tag2">
        </div>

        <div class="form-group">
            <label for="communities">Communities</label>
            <select class="custom-select">
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>

        <label for="license">Choose A License</label>
        <div class="form-group">
            <div class="col-12">
                @include('livewire.projects.datasets.crud.tabs._no-license')
            </div>
            <div class="col-12">
                @include('livewire.projects.datasets.crud.tabs._odbl-license')
            </div>
            <div class="col-12">
                @include('livewire.projects.datasets.crud.tabs._odc-by-license')
            </div>
            <div class="col-12">
                @include('livewire.projects.datasets.crud.tabs._pddl-license')
            </div>
        </div>
        <br/>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

@script
<script>
    (function () {
        let tagify;

        document.addEventListener('livewire:navigating', () => {
            console.log('wire navigating');
            if (tagify) {
                console.log('    destroying tagify');
                tagify.destroy();
            }
        });

        document.addEventListener('livewire:navigated', () => {
            let tagsInput = document.querySelector('#tags');
            tagify = new Tagify(tagsInput);
        }, {once: true})
    })();
</script>
@endscript
