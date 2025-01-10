<?php

namespace App\DTO\DataHQOld;

use Illuminate\Support\Collection;

class ViewStateData
{
    public string $dataType;
    public ?ViewAttr $xattr;
    public ?ViewAttr $yattr;
    public Collection $attrs;

    public function __construct(string $dataType)
    {
        $this->dataType = $dataType;
    }

    public static function makeTableViewStateData(Collection $attrs): ViewStateData
    {
        $viewState = new ViewStateData('table');
        $viewState->attrs = $attrs;
        return $viewState;
    }

    public static function makeChartViewStateData($chartType, ViewAttr $xattr, ViewAttr $yattr): ViewStateData
    {
        $viewState = new ViewStateData($chartType);
        $viewState->xattr = $xattr;
        $viewState->yattr = $yattr;
        return $viewState;
    }
}
