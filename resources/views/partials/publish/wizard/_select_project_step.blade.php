@if ($projects->isNotEmpty())
    <div class="row ">
        <div class="col-12">
            <h4 class="text-center">Create Or Select Project</h4>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-6">
            <h5>Create Project</h5>
            @include('partials._create_project', [
                'createProjectRoute' => $createProjectRoute,
                'cancelRoute' => $cancelRoute
            ])
        </div>
        <div class="col-6 col-left-border">
            <h5>Select Project</h5>
            <div class="form-group">
                <label for="projects">Projects</label>
                <select name="project" class="selectpicker col-lg-8" title="projects"
                        data-live-search="true">
                    @foreach($projects as $project)
                        <option data-token="{{$project->id}}" value="{{$project->id}}">
                            {{$project->name}}
                        </option>
                    @endforeach
                </select>
            </div>
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