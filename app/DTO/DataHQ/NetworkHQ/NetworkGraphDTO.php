<?php

namespace App\DTO\DataHQ\NetworkHQ;

use Illuminate\Support\Collection;
use Livewire\Wireable;

class NetworkGraphDTO implements Wireable
{

    public string $nodeColorAttributeName;
    public Collection $nodeColorAttributeValues;

    public string $edgeColorAttributeName;
    public Collection $edgeColorAttributeValues;

    public string $nodeSizeAttributeName;
    public Collection $nodeSizeAttributeValues;

    public string $edgeDashedAttributeName;
    public Collection $edgeDashedAttributeValues;

    public Collection $nodeIdValues;
    public Collection $nodePositions; // Each node is an array of x,y coordinates
    public Collection $edges; // Each edge is an array of two node ids

    public function __construct() {
        $this->edges = collect();
        $this->nodePositions = collect();
        $this->nodeIdValues = collect();
        $this->nodeSizeAttributeValues = collect();
        $this->nodeColorAttributeValues = collect();
        $this->edgeColorAttributeValues = collect();
        $this->edgeDashedAttributeValues = collect();
        $this->nodeSizeAttributeName = "";
        $this->nodeColorAttributeName = "";
        $this->edgeColorAttributeName = "";
        $this->edgeDashedAttributeName = "";
    }

    public function toLivewire()
    {
        return [
            'nodeColorAttributeName' => $this->nodeColorAttributeName,
            'nodeColorAttributeValues' => $this->nodeColorAttributeValues ?? collect(),
            'edgeColorAttributeName' => $this->edgeColorAttributeName,
            'edgeColorAttributeValues' => $this->edgeColorAttributeValues ?? collect(),
            'edgeDashedAttributeName' => $this->edgeDashedAttributeName,
            'edgeDashedAttributeValues' => $this->edgeDashedAttributeValues ?? collect(),
            'nodeSizeAttributeName' => $this->nodeSizeAttributeName,
            'nodeSizeAttributeValues' => $this->nodeSizeAttributeValues ?? collect(),
            'nodeIdValues' => $this->nodeIdValues,
            'nodePositions' => $this->nodePositions,
            'edges' => $this->edges,
        ];
    }

    public static function fromLivewire($value)
    {
        $dto = new NetworkGraphDTO();

        $dto->nodeColorAttributeName = $value['nodeColorAttributeName'];
        $dto->nodeColorAttributeValues = $value['nodeColorAttributeValues'];
        $dto->edgeColorAttributeName = $value['edgeColorAttributeName'];
        $dto->edgeColorAttributeValues = $value['edgeColorAttributeValues'];
        $dto->edgeDashedAttributeName = $value['edgeDashedAttributeName'];
        $dto->edgeDashedAttributeValues = $value['edgeDashedAttributeValues'];
        $dto->nodeSizeAttributeName = $value['nodeSizeAttributeName'];
        $dto->nodeSizeAttributeValues = $value['nodeSizeAttributeValues'];
        $dto->nodeIdValues = $value['nodeIdValues'];
        $dto->nodePositions = $value['nodePositions'];
        $dto->edges = $value['edges'];

        return $dto;
    }
}
