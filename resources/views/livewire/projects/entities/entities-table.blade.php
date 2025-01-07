<div>
    <x-table.table-search/>
    <table id="entities-with-used-activities" class="table table-hover mt-2"
           style="width: 100%;">
        <thead class="thead-fixed">
        <th>
            <x-table.col-sortable :column="'name'" :sort-col="$sortCol" :sort-asc="$sortAsc">
                Name
            </x-table.col-sortable>
            {{--            <a class="btn tt-none" wire:click="sortBy('name')">--}}
            {{--                <div>Name<i class="fa fa-fw fa-sort"></i></div>--}}
            {{--            </a>--}}
        </th>
        @if(isset($showExperiment))
            <th>Experiment</th>
        @endif
        @foreach($activities as $activity)
            <th>{{$activity->name}}</th>
        @endforeach
        </thead>
        <tbody>
        @foreach($entities as $entity)
            <tr wire:key="{{$entity->id}}">
                <td>
                    @if(isset($experiment))
                        <a href="{{route('projects.experiments.entities.by-name.spread', [$project, $experiment, "name" => urlencode($entity->name)])}}">
                            {{$entity->name}}
                        </a>
                    @else
                        @if(isset($entity->experiments) && $entity->experiments->count() > 0)
                            <a href="{{route('projects.experiments.entities.by-name.spread', [$project, $entity->experiments[0], "name" => urlencode($entity->name)])}}">
                                {{$entity->name}}
                            </a>
                        @else
                            <a href="{{route('projects.entities.show-spread', [$project, $entity])}}">
                                {{$entity->name}}
                            </a>
                        @endif
                    @endif
                </td>
                @if(isset($showExperiment))
                    <td>
                        @if(isset($entity->experiments))
                            @if($entity->experiments->count() > 0)
                                {{$entity->experiments[0]->name}}
                            @endif
                        @endif
                    </td>
                @endif
                @if(isset($usedActivities[$entity->id]))
                    @foreach($usedActivities[$entity->id] as $used)
                        @if($used)
                            <td class="text-center">X</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <div>
        {{$entities->links()}}
    </div>
</div>
