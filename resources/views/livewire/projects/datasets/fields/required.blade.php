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

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
