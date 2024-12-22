<div>
    <div class="col-3 no-padding-left mt-3 form-group has-search">
        <span class="fa fa-search form-control-feedback"></span>
        <input wire:model.live.debounce="search" type="text" class="form-control" placeholder="Search...">
    </div>
    <table id="entities-with-used-activities" class="table table-hover mt-2"
           style="width: 100%;">
        <thead class="thead-fixed">
        <th>
            <a class="btn tt-none">
                <div>Name<i class="fa fa-fw fa-sort"></i></div>
            </a>
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
            <tr>
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
