<form>
    <div class="form-row">
        <div class="col h6">
            <div class="form-control">
                <span>Owner: {{$item->owner->name}}</span>
                <span class="ml-4">Last Updated {{$item->updated_at->diffForHumans()}}</span>
                {{$slot}}
            </div>
        </div>
    </div>
    {{$top ?? ''}}
    <div class="form-group">
        @isset($item->description)
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" type="text"
                      placeholder="Description..." readonly>{{$item->description}}</textarea>
            {{--            <div class="markdown-area mb-2" id="description">--}}
            {{--                @markdown($item->description)--}}
            {{--            </div>--}}
        @else
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" type="text"
                          placeholder="Description..." readonly>{{$item->description}}</textarea>
            </div>
        @endif
    </div>

    {{$bottom ?? ''}}
</form>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('textarea').each(function () {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            }).on('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
@endpush