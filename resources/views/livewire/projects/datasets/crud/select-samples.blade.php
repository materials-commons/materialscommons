<div>
    <x-table.table-search/>
    <table id="entities-with-used-activities" class="table table-hover mt-2"
           style="width: 100%;">
        <thead class="thead-fixed">
        <th>
            <x-table.col-sortable :column="'name'" :sort-col="$sortCol" :sort-asc="$sortAsc">
                Name
            </x-table.col-sortable>
        </th>
        <th>Experiment</th>
        @foreach($activities as $activity)
            <th>{{$activity->name}}</th>
        @endforeach
        </thead>
        <tbody>
        @foreach($entities as $entity)
            <tr wire:key="{{$entity->id}}">
                <td>
                    <div>
                        <input type="checkbox" id="{{$entity->uuid}}"
                               {{$this->entityInDataset($entity->id) ? 'checked' : ''}}
                               wire:click="toggleEntity({{$entity->id}})">
                        <a href="{{route('projects.experiments.entities.by-name.spread', [$project, $entity->experiments[0], "name" => urlencode($entity->name)])}}"
                           class="no-underline">
                            {{$entity->name}}
                        </a>
                    </div>
                </td>
                <td>
                    @if(isset($entity->experiments))
                        @if($entity->experiments->count() > 0)
                            {{$entity->experiments[0]->name}}
                        @endif
                    @endif
                </td>
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

