@extends('layouts.app')

@section('pageTitle', "{$project->name} - Compare Activities")

@section('nav')
    @include('layouts.navs.app.project')
@stop

@section('content')
    <x-card>
        <x-slot name="header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Comparing: {{$activity1->name}} from {{$activity1->entities->first()->name}} vs {{$activity2->name}} from {{$activity2->entities->first()->name}}</span>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Compare
                </a>
            </div>
        </x-slot>

        <x-slot name="body">
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
                    <x-card>
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
                    </x-card>
                </div>
                <div class="col-6">
                    <x-card>
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
                    </x-card>
                </div>
            </div>
        </x-slot>
    </x-card>
@endsection