@props([
    /** @var \Illuminate\Database\Eloquent\Model */
    'activity1',
    /** @var \App\Models\Project */
    'project',
    /** @var \App\DTO\Activities\AttributesComparerState */
    'activityAttributesState',
    /** @var \Illuminate\Support\Collection */
    'activity1EntityStateOutAttributes',
    /** @var \App\DTO\Activities\AttributesComparerState */
    'entityAttributesState',
    /** @var \Illuminate\Database\Eloquent\Model */
    'activity2',
    /** @var \Illuminate\Support\Collection */
    'activity2EntityStateOutAttributes'
])

<div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5>Highlighting Legend:</h5>
                <ul class="mb-0">
                    <li>
                        <span class="badge bg-primary text-white">Blue</span>
                        - Attributes that appear only on
                        one side
                    </li>
                    <li>
                        <span class="badge bg-warning">Yellow</span>
                        - Attributes with the same name but
                        different values
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <h5 class="ms-1">{{$activity1->entities->first()->name}}</h5>
            <div class="white-box h-100">

                <x-activities.compare.single-activity :project="$project"
                                                      :activity="$activity1"
                                                      :side="'left'"
                                                      :activity-attributes="$activity1->attributes"
                                                      :activity-comparer-state="$activityAttributesState"
                                                      :entity-state-attributes="$activity1EntityStateOutAttributes"
                                                      :entity-state-comparer-state="$entityAttributesState"/>
            </div>
        </div>
        <div class="col-lg-6">
            <h5 class="ms-1">{{$activity2->entities->first()->name}}</h5>
            <div class="white-box h-100">

                <x-activities.compare.single-activity :project="$project"
                                                      :activity="$activity2"
                                                      :side="'right'"
                                                      :activity-attributes="$activity2->attributes"
                                                      :activity-comparer-state="$activityAttributesState"
                                                      :entity-state-attributes="$activity2EntityStateOutAttributes"
                                                      :entity-state-comparer-state="$entityAttributesState"/>
            </div>
        </div>
    </div>
</div>
