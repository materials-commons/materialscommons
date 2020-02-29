@extends('layouts.app')

@section('pageTitle', 'Public Data')

@section('nav')
    @include('layouts.navs.public')
@stop
@section('content')
    @component('components.card')
        @slot('header')
            Dataset Details Step
        @endslot

        @slot('body')
            <form method="post" action="{{route('projects.datasets.store', [$project])}}" id="dataset_create">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">Name</label>
                    <input class="form-control" id="name" name="name" type="text" value=""
                           placeholder="Name...">
                </div>

                <div class="form-group">
                    <label for="authors">Authors and Affiliations</label>
                    <input class="form-control" id="authors" name="authors" type="text"
                           value=""
                           placeholder="Authors...">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" type="text"
                              value=""
                              placeholder="Description..."></textarea>
                </div>

                <div class="form-group">
                    <label for="doi">DOI</label>
                    <span class="col-8">
                            None
                            <a href="#" onclick="changeActionAndSubmit()" style="margin-left: 8px">
                                Assign DOI
                            </a>
                        </span>
                </div>

                <div class="form-group">
                    <label for="license">License</label>
                    <select name="license" class="selectpicker col-lg-8" data-live-search="true"
                            title="License">
                        <option data-token="No License" value="No License">No License</option>
                        <option data-token="Public Domain Dedication and License (PDDL)"
                                value="Public Domain Dedication and License (PDDL)">
                            Public Domain Dedication and License (PDDL)
                        </option>
                        <option data-token="Attribution License (ODC-By)"
                                value="Attribution License (ODC-By)">
                            Attribution License (ODC-By)
                        </option>
                        <option data-token="Open Database License (ODC-ODbL)"
                                value="Open Database License (ODC-ODbL)">
                            Open Database License (ODC-ODbL)
                        </option>
                    </select>
                    <a href="https://opendatacommons.org/licenses/index.html" target="_blank">License Summaries</a>
                </div>

                <div class="form-group">
                    <label for="communities">Communities</label>
                    <select name="communities[]" class="selectpicker col-lg-8"
                            title="communities"
                            data-live-search="true" multiple>
                        @foreach($communities as $community)
                            <option data-token="{{$community->id}}" value="{{$community->id}}">
                                {{$community->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input class="form-control" id="tags" name="tags">
                </div>

                <input hidden id="project_id" name="project_id" value="{{$project->id}}">
                <input type="hidden" name="action" value="" id="action"/>

                <div class="float-right">
                    <a href="{{route('projects.datasets.index', ['project' => $project->id])}}"
                       class="action-link danger mr-3">
                        Cancel
                    </a>

                    {{--                    <a class="action-link mr-3" href="#" id="add-assets-button"--}}
                    {{--                       onclick="setActionAndSubmit('assets')">--}}
                    {{--                        Save And Add Files--}}
                    {{--                    </a>--}}

                    <a class="action-link mr-3" href="{{route('public.publish.wizard.upload_files')}}"
                       id="add-assets-button">
                        Save And Add Files
                    </a>
                </div>
            </form>
            <br>
            @include('common.errors')
        @endslot
    @endcomponent
@stop