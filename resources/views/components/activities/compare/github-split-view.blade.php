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

<div class="github-split-container">
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
                    <li><span class="github-gutter removed d-inline-block px-2">-</span> Left side: Attributes from {{$activity1->name}} ({{$activity1->entities->first()->name}})</li>
                    <li><span class="github-gutter added d-inline-block px-2">+</span> Right side: Attributes from {{$activity2->name}} ({{$activity2->entities->first()->name}})</li>
                    <li>Empty cells indicate attribute doesn't exist in that activity</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Activity Descriptions --}}
    @if(!blank($activity1->description) || !blank($activity2->description))
        <div class="row mb-3">
            <div class="col-12">
                <h5>Description</h5>
                <table class="github-diff-table">
                    <tbody>
                        <tr>
                            <td class="github-gutter removed">-</td>
                            <td class="github-line removed">{{$activity1->description ?: 'No description'}}</td>
                            <td class="github-gutter added">+</td>
                            <td class="github-line added">{{$activity2->description ?: 'No description'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Settings Diff --}}
    <div class="row">
        <div class="col-12">
            <h5><u>Settings</u></h5>
            <x-activities.compare.github-split-attributes
                :comparer-state="$activityComparerState"
                :attributes1="$activity1Attributes"
                :attributes2="$activity2Attributes"/>
        </div>
    </div>

    {{-- Measurements Diff --}}
    <div class="row mt-4">
        <div class="col-12">
            <h5><u>Measurements</u></h5>
            <x-activities.compare.github-split-attributes
                :comparer-state="$entityStateComparerState"
                :attributes1="$activity1EntityStateAttributes"
                :attributes2="$activity2EntityStateAttributes"/>
        </div>
    </div>
</div>

<style>
    .github-split-container {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif;
        font-size: 0.875rem;
    }

    .github-diff-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #d0d7de;
        margin-bottom: 16px;
        font-family: 'SF Mono', Monaco, 'Cascadia Mono', 'Roboto Mono', Menlo, Consolas, monospace;
        font-size: 12px;
        line-height: 1.1;       /* Add tighter line-height to table */
    }

    .github-diff-table tbody tr {
        border-top: 1px solid #d0d7de;
    }

    .github-gutter {
        width: 40px;
        min-width: 40px;
        padding: 0px 8px;       /* Much tighter - 1px vertical */
        font-family: 'SF Mono', Monaco, monospace;
        font-size: 12px;
        line-height: 1.0;       /* Tighter line-height */
        text-align: center;
        vertical-align: top;
        cursor: default;
        user-select: none;
        border-right: 1px solid #d0d7de;
    }

    .github-line {
        padding: 0px 8px;
        line-height: 1.0;
        vertical-align: top;
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* Removed (left side) */
    .github-gutter.removed {
        background-color: #ffebe9;
        color: #82071e;
    }

    .github-line.removed {
        background-color: #ffebe9;
    }

    .github-line.removed-highlight {
        background-color: #ffd7d5;
    }

    /* Added (right side) */
    .github-gutter.added {
        background-color: #dafbe1;
        color: #055d20;
    }

    .github-line.added {
        background-color: #dafbe1;
    }

    .github-line.added-highlight {
        background-color: #abf2bc;
    }

    /* Empty/neutral */
    .github-gutter.empty {
        background-color: #f6f8fa;
        color: #8c959f;
    }

    .github-line.empty {
        background-color: #f6f8fa;
    }

    /* Unchanged */
    .github-gutter.unchanged {
        background-color: #ffffff;
        color: #656d76;
    }

    .github-line.unchanged {
        background-color: #ffffff;
    }

    .github-attr-name {
        font-weight: 600;
        color: #1f2328;
        margin-right: 8px;      /* Add space between name and value */
    }

    .github-attr-value {
        color: #0550ae;
        margin-left: 4px;       /* Add a bit more space from the name */
    }

    /* Highlight changed values within line */
    .value-changed {
        background-color: rgba(234, 74, 170, 0.15);
        padding: 2px 4px;
        border-radius: 2px;
        display: inline-block;
        margin-left: 4px;       /* Add margin so highlight doesn't touch the name */
    }
</style>
