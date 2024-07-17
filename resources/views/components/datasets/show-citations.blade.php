<div class="form-group mt-2">
    <label for="citations">Citations</label>
    @if($hasCitations)
        <div class="row">
            <div class="col-6">
                <table class="table table-sm table-hover ml-3">
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
                            {{--                            <td><a href="{{route('public.datasets.citations.dataset', [$dataset])}}">{{count($citations->dataset->message->reference)}}</a></td>--}}
                            <td class="font-weight-normal">{{count($citations->dataset->message->reference)}}</td>
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
                                {{--                                <td>--}}
                                {{--                                    <a href="{{route('public.datasets.citations.papers', ['dataset' => $dataset, 'doi' => $paper->message->DOI])}}">{{count($paper->message->reference)}}</a>--}}
                                {{--                                </td>--}}
                                <td class="font-weight-normal">{{count($paper->message->reference)}}</td>
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
