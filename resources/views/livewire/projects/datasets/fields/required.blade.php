<div>
    <form>
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" placeholder="Title...">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" placeholder="Description..."></textarea>
        </div>

        <div class="form-group">
            <label for="summary">Summary</label>
            <input type="text" class="form-control" id="summary" placeholder="Summary...">
        </div>

        <ul x-sort>
            <li x-sort:item>ABC</li>
            <li x-sort:item>DEF</li>
            <li x-sort:item>GHI</li>
        </ul>

        <div class="form-group">
            <label for="tags">Tags</label>
            <input class="form-control" id="tags" name="tags" value="tag1, tag2">
        </div>

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
