<?php

namespace App\Http\Resources\Globus;

use Illuminate\Http\Resources\Json\JsonResource;

class GlobusTransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
