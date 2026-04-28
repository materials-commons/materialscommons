<div class="d-flex justify-content-center w-100">
    <ul class="steps">
        @if(blank($dataset->description))
            <li class="step step-error">
                <a class="step-content" href="{{$defaultRoute}}">
                    <span class="step-circle"><i class="fa fas fa-exclamation fa-fw"></i></span>
                    <span class="step-text">Overview</span>
                </a>
            </li>
        @else
            <li class="step step-success">
                <a class="step-content" href="{{$defaultRoute}}">
                    <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                    <span class="step-text">Overview</span>
                </a>
            </li>
        @endif

        @if($dataset->hasSelectedFiles())
            <li class="step step-success" id="files-step">
                <a class="step-content" href="{{$filesRoute}}">
                    <span class="step-circle"><i class="fa fas fa-check fa-fw" id="files-circle"></i></span>
                    <span class="step-text">Files</span>
                </a>
            </li>
        @else
            <li class="step step-error" id="files-step">
                <a class="step-content" href="{{$filesRoute}}">
                            <span class="step-circle"><i class="fa fas fa-exclamation fa-fw"
                                                         id="files-circle"></i></span>
                    <span class="step-text">Files</span>
                </a>
            </li>
        @endif

        @if($entities->count() != 0)
            <li class="step step-success" id="samples-step">
                <a class="step-content" href="{{$samplesRoute}}">
                    <span class="step-circle"><i class="fa fas fa-check fa-fw" id="samples-circle"></i></span>
                    <span class="step-text">Samples (Optional)</span>
                </a>
            </li>
        @else
            <li class="step" id="samples-step">
                <a class="step-content" href="{{$samplesRoute}}">
                    <span class="step-circle"><i class="fa fas fa-circle fa-fw" id="samples-circle"></i></span>
                    <span class="step-text">Samples (Optional)</span>
                </a>
            </li>
        @endif

        @if($dataset->workflows->count() != 0)
            <li class="step step-success" id="workflows-step">
                <a class="step-content" href="{{$workflowsRoute}}">
                    <span class="step-circle"><i class="fa fas fa-check fa-fw" id="workflows-circle"></i></span>
                    <span class="step-text">Workflow (Optional)</span>
                </a>
            </li>
        @else
            <li class="step" id="workflows-step">
                <a class="step-content" href="{{$workflowsRoute}}">
                            <span class="step-circle"><i class="fa fas fa-circle fa-fw"
                                                         id="workflows-circle"></i></span>
                    <span class="step-text">Workflow (Optional)</span>
                </a>
            </li>
        @endif

        @if(is_null($dataset->published_at))
            <li class="step">
                <div class="step-content">
                    <span class="step-circle"><i class="fa fas fa-circle fa-fw"></i></span>
                    <span class="step-text">Published</span>
                </div>
            </li>
        @else
            <li class="step step-success">
                <div class="step-content">
                    <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                    <span class="step-text">Published</span>
                </div>
            </li>
        @endif
    </ul>
</div>
<div class="d-flex justify-content-center w-100 mt-3">
    @include('app.projects.datasets._dataset-issues')
</div>
