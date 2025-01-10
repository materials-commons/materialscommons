<?php

namespace App\DTO\DataHQ;

use JsonSerializable;

class Chart implements JsonSerializable
{
    public string $name; // Name of the chart
    public string $description; // Description of the chart
    public string $xAxisTitle; // The chart's x axis title
    public string $yAxisTitle; // The chart's y axis title
    public string $xAxisAttribute; // The chart's x axis attribute
    public string $xAxisAttributeType; // The chart's x axis attribute type (entity or activity attribute)
    public string $yAxisAttribute; // The chart's y axis attribute
    public string $yAxisAttributeType; // The chart's y axis attribute type (entity or activity attribute)

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->xAxisTitle = $data['xAxisTitle'];
        $this->yAxisTitle = $data['yAxisTitle'];
        $this->xAxisAttribute = $data['xAxisAttribute'];
        $this->xAxisAttributeType = $data['xAxisAttributeType'];
        $this->yAxisAttribute = $data['yAxisAttribute'];
        $this->yAxisAttributeType = $data['yAxisAttributeType'];
    }

    public function jsonSerialize(): array
    {
        return [
            'name'               => $this->name,
            'description'        => $this->description,
            'xAxisTitle'         => $this->xAxisTitle,
            'yAxisTitle'         => $this->yAxisTitle,
            'xAxisAttribute'     => $this->xAxisAttribute,
            'xAxisAttributeType' => $this->xAxisAttributeType,
            'yAxisAttribute'     => $this->yAxisAttribute,
            'yAxisAttributeType' => $this->yAxisAttributeType,
        ];
    }
}