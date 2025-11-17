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
    public Collection $nodeIdValues;
    public Collection $nodePositions; // Each node is an array of x,y coordinates
    public Collection $edges; // Each edge is an array of two node ids

    public function toLivewire()
    {
        return [
            'nodeColorAttributeName' => $this->nodeColorAttributeName,
            'nodeColorAttributeValues' => $this->nodeColorAttributeValues,
            'edgeColorAttributeName' => $this->edgeColorAttributeName,
            'edgeColorAttributeValues' => $this->edgeColorAttributeValues,
            'nodeSizeAttributeName' => $this->nodeSizeAttributeName,
            'nodeSizeAttributeValues' => $this->nodeSizeAttributeValues,
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
        $dto->nodeSizeAttributeName = $value['nodeSizeAttributeName'];
        $dto->nodeSizeAttributeValues = $value['nodeSizeAttributeValues'];
        $dto->nodeIdValues = $value['nodeIdValues'];
        $dto->nodePositions = $value['nodePositions'];
        $dto->edges = $value['edges'];

        return $dto;
    }
}
