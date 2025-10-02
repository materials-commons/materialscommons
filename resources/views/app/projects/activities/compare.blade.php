@extends('layouts.app')

@section('pageTitle', "{$project->name} - Compare Activities")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Activity Comparison</span>
                <div>
                    <button type="button" class="btn btn-sm btn-primary mr-1 view-btn active" data-view="unified">
                        <i class="fas fa-align-left"></i> Unified
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary mr-1 view-btn" data-view="github">
                        <i class="fab fa-github"></i> Split
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary mr-2 view-btn" data-view="original">
                        <i class="fas fa-columns"></i> Original
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </x-slot>

        <x-slot name="body">
            {{-- Unified Diff View (Default) --}}
            <div id="unifiedView" class="view-content">
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

            {{-- GitHub Split View --}}
            <div id="githubView" class="view-content" style="display: none;">
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

            {{-- Original Split View --}}
            <div id="originalView" class="view-content" style="display: none;">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5>Highlighting Legend:</h5>
                            <ul class="mb-0">
                                <li><span class="badge bg-primary text-white">Blue</span> - Attributes that appear only on
                                    one side
                                </li>
                                <li><span class="badge bg-warning">Yellow</span> - Attributes with the same name but
                                    different values
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <x-card-white>
                            <x-slot name="header">
                                <h5>{{$activity1->entities->first()->name}}</h5>
                            </x-slot>
                            <x-slot name="body">
                                <x-activities.compare.single-activity :project="$project"
                                                                      :activity="$activity1"
                                                                      :side="'left'"
                                                                      :activity-attributes="$activity1->attributes"
                                                                      :activity-comparer-state="$activityAttributesState"
                                                                      :entity-state-attributes="$activity1EntityStateOutAttributes"
                                                                      :entity-state-comparer-state="$entityAttributesState"/>
                            </x-slot>
                        </x-card-white>
                    </div>
                    <div class="col-6">
                        <x-card-white>
                            <x-slot name="header">
                                <h5>{{$activity2->entities->first()->name}}</h5>
                            </x-slot>
                            <x-slot name="body">
                                <x-activities.compare.single-activity :project="$project"
                                                                      :activity="$activity2"
                                                                      :side="'right'"
                                                                      :activity-attributes="$activity2->attributes"
                                                                      :activity-comparer-state="$activityAttributesState"
                                                                      :entity-state-attributes="$activity2EntityStateOutAttributes"
                                                                      :entity-state-comparer-state="$entityAttributesState"/>
                            </x-slot>
                        </x-card-white>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-card>

    <script>
        // View switcher
        const viewButtons = document.querySelectorAll('.view-btn');
        const views = {
            'unified': document.getElementById('unifiedView'),
            'github': document.getElementById('githubView'),
            'original': document.getElementById('originalView')
        };

        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
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
