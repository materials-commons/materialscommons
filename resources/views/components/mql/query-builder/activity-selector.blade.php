<div>
    <div class="row mb-2">
        @if($category == "computational")
            <span class="mr-2 ml-3">In Activity:</span>
        @else
            <span class="mr-2 ml-3">In Process:</span>
        @endif
    </div>
    <select id="activities">
        <option value=""></option>
        @foreach($activities as $activity)
            <option value="{{$activity->name}}">{{$activity->name}}</option>
        @endforeach
    </select>
    {{--            <div class="row mt-2">--}}
    {{--                <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Process</a>--}}
    {{--            </div>--}}
    @push('scripts')
        <script>
            $(document).ready(() => {
                function setupHavingProcess() {
                    let findMatchingRoute = "{{route('api.queries.find-matching-entities', [$project])}}";
                    let apiToken = "{{auth()->user()->api_token}}";
                    let api = $('#entities-with-used-activities').DataTable();
                    $('#activities').on('change', function () {
                        let selected = $(this).val();
                        if (selected === '') {
                            api.search('').columns().search('').draw();
                            return;
                        }
                        axios.post(`${findMatchingRoute}`, {
                                activities: [
                                    {
                                        name: selected,
                                        operator: "in"
                                    }
                                ]
                            },
                            {
                                headers: {
                                    Authorization: `Bearer ${apiToken}`
                                }
                            }
                        ).then((r) => {
                            if (r.data.entities.length !== 0) {
                                let searchStr = "";
                                for (let i = 0; i < r.data.entities.length; i++) {
                                    let e = r.data.entities[i];
                                    if (i === 0) {
                                        searchStr = e;
                                    } else {

                                        searchStr = searchStr + `|^${e}$`;
                                    }
                                }
                                // api.search('').columns().search('').draw();
                                api.column(0).search(searchStr, true, false).draw();
                            }
                        }).catch((e) => {
                            console.log("error: ", e);
                        });
                    });
                }

                setupHavingProcess();
            });

        </script>
    @endpush
</div>