@extends('layouts.app')

@section('pageTitle', "{$project->name} - Compare Activities")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span>Activity Comparison</span>
        <div>
            <button type="button" class="btn btn-sm btn-primary me-1 view-btn active" data-view="github">
                <i class="fab fa-github"></i> Split
            </button>

            <button type="button" class="btn btn-sm btn-secondary me-1 view-btn" data-view="unified">
                <i class="fas fa-align-left"></i> Unified
            </button>

            <button type="button" class="btn btn-sm btn-secondary me-2 view-btn" data-view="original">
                <i class="fas fa-columns"></i> Card
            </button>
        </div>
    </div>

    <div>
        {{-- GitHub Split View (Default) --}}
        <div id="githubView" class="view-content" style="display: block;">
            <div class="white-box">
                <x-activities.compare.github-split-view
                    :activity1="$activity1"
                    :activity2="$activity2"
                    :project="$project"
                    :activity-comparer-state="$activityAttributesState"
                    :entity-state-comparer-state="$entityAttributesState"
                    :activity1-attributes="$activity1->attributes"
                    :activity2-attributes="$activity2->attributes"
                    :activity1-entity-state-attributes="$activity1EntityStateOutAttributes"
                    :activity2-entity-state-attributes="$activity2EntityStateOutAttributes"/>
            </div>
        </div>

        {{-- Unified Diff View --}}
        <div id="unifiedView" class="view-content" style="display: none;">
            <div class="white-box">
                <x-activities.compare.unified-diff-view
                    :activity1="$activity1"
                    :activity2="$activity2"
                    :project="$project"
                    :activity-comparer-state="$activityAttributesState"
                    :entity-state-comparer-state="$entityAttributesState"
                    :activity1-attributes="$activity1->attributes"
                    :activity2-attributes="$activity2->attributes"
                    :activity1-entity-state-attributes="$activity1EntityStateOutAttributes"
                    :activity2-entity-state-attributes="$activity2EntityStateOutAttributes"/>
            </div>
        </div>

        {{-- Original Split Card View --}}
        <div id="originalView" class="view-content" style="display: none;">
            <x-activities.compare.card-diff-view :activity1="$activity1"
                                                 :activity2="$activity2"
                                                 :project="$project"
                                                 :activity-attributes-state="$activityAttributesState"
                                                 :activity1-entity-state-out-attributes="$activity1EntityStateOutAttributes"
                                                 :entity-attributes-state="$entityAttributesState"
                                                 :activity2-entity-state-out-attributes="$activity2EntityStateOutAttributes"/>
        </div>

        <script>
            // View switcher
            const viewButtons = document.querySelectorAll('.view-btn');
            const views = {
                'unified': document.getElementById('unifiedView'),
                'github': document.getElementById('githubView'),
                'original': document.getElementById('originalView')
            };

            viewButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetView = this.getAttribute('data-view');

                    // Update button states
                    viewButtons.forEach(btn => {
                        btn.classList.remove('active', 'btn-primary');
                        btn.classList.add('btn-secondary');
                    });
                    this.classList.remove('btn-secondary');
                    this.classList.add('active', 'btn-primary');

                    // Show/hide views
                    Object.keys(views).forEach(viewKey => {
                        if (viewKey === targetView) {
                            views[viewKey].style.display = 'block';
                        } else {
                            views[viewKey].style.display = 'none';
                        }
                    });
                });
            });
        </script>
@endsection
