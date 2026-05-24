@props([
    'activity1',
    'activity2',
    'project',
    'activityComparerState',
    'entityStateComparerState',
    'activity1Attributes',
    'activity2Attributes',
    'activity1EntityStateAttributes',
    'activity2EntityStateAttributes',
])

<div class="unified-diff-container">
    {{-- Activity Info Header --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Comparing:</strong>
                        <span class="text-danger">{{$activity1->name}}</span> from {{$activity1->entities->first()->name}}
                        <strong>vs</strong>
                        <span class="text-success">{{$activity2->name}}</span> from {{$activity2->entities->first()->name}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <h6>Diff Legend:</h6>
                <ul class="mb-0 small">
                    <li><span class="diff-line removed d-inline-block px-2">- Removed</span> - Attributes only in {{$activity1->name}}</li>
                    <li><span class="diff-line added d-inline-block px-2">+ Added</span> - Attributes only in {{$activity2->name}}</li>
                    <li><span class="diff-line changed d-inline-block px-2">~ Changed</span> - Attributes with different values</li>
                    <li><span class="diff-line unchanged d-inline-block px-2">&nbsp; Unchanged</span> - Attributes with same values</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Activity Descriptions --}}
    @if(!blank($activity1->description) || !blank($activity2->description))
        <div class="row mb-3">
            <div class="col-12">
                <h5>Description</h5>
                @if($activity1->description !== $activity2->description)
                    @if(!blank($activity1->description))
                        <div class="diff-line removed mb-1">
                            <strong>-</strong> {{$activity1->description}}
                        </div>
                    @endif
                    @if(!blank($activity2->description))
                        <div class="diff-line added">
                            <strong>+</strong> {{$activity2->description}}
                        </div>
                    @endif
                @else
                    <div class="diff-line unchanged">
                        <strong>&nbsp;</strong> {{$activity1->description}}
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Settings Diff --}}
    <div class="row">
        <div class="col-12">
            <h5><u>Settings</u></h5>
            <x-activities.compare.unified-diff-attributes
                :comparer-state="$activityComparerState"
                :attributes1="$activity1Attributes"
                :attributes2="$activity2Attributes"/>
        </div>
    </div>

    {{-- Measurements Diff --}}
    <div class="row mt-4">
        <div class="col-12">
            <h5><u>Measurements</u></h5>
            <x-activities.compare.unified-diff-attributes
                :comparer-state="$entityStateComparerState"
                :attributes1="$activity1EntityStateAttributes"
                :attributes2="$activity2EntityStateAttributes"/>
        </div>
    </div>
</div>

<style>
    .unified-diff-container {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', monospace;
        font-size: 0.9rem;
    }

    .diff-line {
        padding: 4px 8px;
        margin: 1px 0;
        border-left: 4px solid transparent;
        line-height: 1.5;
    }

    .diff-line.removed {
        background-color: #ffdddd;
        border-left-color: #d00;
        color: #600;
    }

    .diff-line.added {
        background-color: #ddffdd;
        border-left-color: #0a0;
        color: #060;
    }

    .diff-line.changed {
        background-color: #ffffdd;
        border-left-color: #fa0;
        color: #660;
    }

    .diff-line.unchanged {
        background-color: #f8f9fa;
        border-left-color: #ccc;
        color: #666;
    }

    .diff-attr-name {
        font-weight: 600;
        display: inline-block;
        min-width: 200px;
    }

    .diff-attr-value {
        display: inline-block;
    }

    .diff-symbol {
        font-weight: bold;
        margin-right: 8px;
    }
</style>
