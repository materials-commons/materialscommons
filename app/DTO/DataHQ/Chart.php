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

    public function __construct(string $name, string $description, string $xAxisTitle, string $yAxisTitle,
                                string $xAxisAttribute, string $xAxisAttributeType, string $yAxisAttribute,
                                string $yAxisAttributeType)
    {
        $this->name = $name;
        $this->description = $description;
        $this->xAxisTitle = $xAxisTitle;
        $this->yAxisTitle = $yAxisTitle;
        $this->xAxisAttribute = $xAxisAttribute;
        $this->xAxisAttributeType = $xAxisAttributeType;
        $this->yAxisAttribute = $yAxisAttribute;
        $this->yAxisAttributeType = $yAxisAttributeType;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['name'], $data['description'], $data['xAxisTitle'], $data['yAxisTitle'],
            $data['xAxisAttribute'], $data['xAxisAttributeType'], $data['yAxisAttribute'], $data['yAxisAttributeType']);
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