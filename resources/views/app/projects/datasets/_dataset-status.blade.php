<ul class="steps">
    @if(blank($dataset->description))
        <li class="step step-error">
            <div class="step-content">
                <span class="step-circle"><i class="fa fas fa-exclamation fa-fw"></i></span>
                <span class="step-text">Overview</span>
            </div>
        </li>
    @else
        <li class="step step-success">
            <div class="step-content">
                <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                <span class="step-text">Overview</span>
            </div>
        </li>
    @endif

    @if($dataset->hasSelectedFiles())
        <li class="step step-success">
            <div class="step-content">
                <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                <span class="step-text">Files</span>
            </div>
        </li>
    @else
        <li class="step step-error">
            <div class="step-content">
                <span class="step-circle"><i class="fa fas fa-exclamation fa-fw"></i></span>
                <span class="step-text">Files</span>
            </div>
        </li>
    @endif

    @if($entities->count() != 0)
        <li class="step step-success">
            <div class="step-content">
                <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                <span class="step-text">Samples (Optional)</span>
            </div>
        </li>
    @else
        <li class="step">
            <div class="step-content">
                <span class="step-circle">3</span>
                <span class="step-text">Samples (Optional)</span>
            </div>
        </li>
    @endif

    @if($dataset->workflows->count() != 0)
        <li class="step step-success">
            <div class="step-content">
                <span class="step-circle"><i class="fa fas fa-check fa-fw"></i></span>
                <span class="step-text">Workflow (Optional)</span>
            </div>
        </li>
    @else
        <li class="step">
            <div class="step-content">
                <span class="step-circle">4</span>
                <span class="step-text">Workflow (Optional)</span>
            </div>
        </li>
    @endif

    @if(is_null($dataset->published_at))
        <li class="step">
            <div class="step-content">
                <span class="step-circle">5</span>
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