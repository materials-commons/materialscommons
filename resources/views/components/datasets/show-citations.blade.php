<div class="form-group mt-2">
    <label for="citations">Citations</label>
    @if($hasCitations)
        <div class="row">
            <div class="col-6">
                <table class="table table-sm table-hover ml-3">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Citations</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($citations->dataset->status))
                        @php
                            $refCount = 0;
                            if (isset($citations->dataset->message->{"is-referenced-by-count"})) {
                                $refCount = $citations->dataset->message->{"is-referenced-by-count"};
                            }
                        @endphp
                        <tr>
                            <td class="font-weight-normal">Dataset</td>
                            {{--<td><a href="{{route('public.datasets.citations.dataset', [$dataset])}}">{{$refCount}}</a></td>--}}
                            <td class="font-weight-normal">{{$refCount}}</td>
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
                                @php
                                    $refCount = 0;
                                    if (isset($paper->message->{"is-referenced-by-count"})) {
                                        $refCount = $paper->message->{"is-referenced-by-count"};
                                    }
                                @endphp
                                {{--<td>--}}
                                {{--   <a href="{{route('public.datasets.citations.papers', ['dataset' => $dataset, 'doi' => $paper->message->DOI])}}">{{$refCount}}</a>--}}
                                {{--</td>--}}
                                <td class="font-weight-normal">{{$refCount}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <ul class="list-unstyled" style="font-weight: normal">
            <li>No citations data found for this dataset or it's associated papers</li>
        </ul>
    @endif
</div>
