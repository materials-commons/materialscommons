<?php

namespace App\View\Components\Datahq;

use App\Models\Project;
use App\Traits\Attributes\AttributeDetails;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowAttributeDetails extends Component
{
    use AttributeDetails;

    public Project $project;
    public string $attrType;
    public string $attrName;

    public function __construct(Project $project, string $attrType, string $attrName)
    {
        $this->project = $project;
        $this->attrType = $attrType;
        $this->attrName = $attrName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if ($this->attrType === 'entity') {
            $details = $this->getSampleAttributeDetails($this->project->id, $this->attrName);
        } else {
            // $this->attrType == 'activity'
            $details = $this->getProcessAttributeDetails($this->project->id, $this->attrName);
        }
        return view('components.datahq.show-attribute-details', [
            'details' => $details,
        ]);
    }
}
