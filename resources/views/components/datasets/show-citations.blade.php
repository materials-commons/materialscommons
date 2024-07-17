<div class="form-group mt-2">
    <label for="citations">Citations</label>
    @if($hasCitations)
        <div class="row">
            <div class="col-6">
                <table class="table table-sm table-hover ml-2">
                    <thead>
                    <tr>
                        <th>Dataset/Paper</th>
                        <th>Citations</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($citations->dataset->status))
                        <tr>
                            <td class="font-weight-normal">Dataset citations</td>
                            <td></td>
                        </tr>
                    @else
                        <tr>
                            <td class="font-weight-normal">Dataset has no citations</td>
                            <td></td>
                        </tr>
                    @endif
                    @if(count($citations->papers) != 0)
                        @foreach($citations->papers as $paper)
                            <tr>
                                <td class="font-weight-normal">{{$paper->message->title[0]}}</td>
                                <td><a href="#" class="ml-2">{{count($paper->message->reference)}}</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <ul class="list-unstyled" style="font-weight: normal">
            <li>No citations found for this dataset or associated papers</li>
        </ul>
    @endif
</div>
