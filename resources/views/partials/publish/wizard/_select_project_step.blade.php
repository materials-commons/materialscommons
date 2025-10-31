@if ($projects->isNotEmpty())
    <div class="row ">
        <div class="col-12">
            <h4 class="text-center">Create Or Select Project</h4>
        </div>
    </div>
    <br>

    <div class="row">

    <div class="col-6 col-left-border">
            <h5>Select Project</h5>
            <form class="col-12">
                <div class="mb-3">
                    <label for="projects">Projects</label>
                    <select name="project" class="form-select" title="projects">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option data-token="{{$project->id}}" value="{{$project->id}}">
                                {{$project->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-9">
                    <div class="float-end">
                        <a class="action-link danger me-3" href="#">Cancel</a>
                        <a class="action-link me-3" href="#">Use Selected Project</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-10">
            @include('partials._create_project', [
                'createProjectRoute' => $createProjectRoute,
                'cancelRoute' => $cancelRoute
            ])
        </div>
    </div>
@endif
