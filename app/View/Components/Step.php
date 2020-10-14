<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Step extends Component
{
    public $type;
    public $text;
    public $step;
    public $optional;
    public $offset;

    public function __construct($type, $text, $step, $optional, $offset)
    {
        $this->type = $type;
        $this->text = $text;
        $this->step = $step;
        $this->optional = $optional;
        $this->offset = $offset;
    }

    private function stepCircle()
    {
        switch ($this->type) {
            case "success":
                return '<i class="fa fas fa-fw fa-check"></i>';
            case "error":
                return '<i class="fa fas fa-fw fa-exclamation"></i>';
            default:
                return $this->step;
        }
    }

    private function stepClass()
    {
        return $this->type === "" ? "" : "step-{$this->type}";
    }

    private function stepText()
    {
        if (!$this->optional) {
            return $this->text;
        }

        return "{$this->text} <br><span class='ml-{$this->offset}'>(Optional)</span>";
    }

    public function render()
    {
        $stepClass = $this->stepClass();
        $stepCircleContent = $this->stepCircle();
        $stepText = $this->stepText();
        return <<<blade
        <li class="step $stepClass">
            <a class="step-content" href="#">
                <span class="step-circle">$stepCircleContent</span>
                <span class="step-text">$stepText</span>
            </a>
        </li>
blade;
//        return view('components.step');
    }
}
