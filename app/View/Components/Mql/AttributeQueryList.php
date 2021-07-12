<?php

namespace App\View\Components\Mql;

use Illuminate\View\Component;

class AttributeQueryList extends Component
{
    public $attrs;
    public $detailsRouteName;
    public $formVarName;
    public $project;

    public function __construct($attrs, $formVarName, $detailsRouteName, $project)
    {
        $this->attrs = $attrs;
        $this->detailsRouteName = $detailsRouteName;
        $this->formVarName = $formVarName;
        $this->project = $project;
    }

    public function render()
    {
        return view('components.mql.attribute-query-list');
    }
}
