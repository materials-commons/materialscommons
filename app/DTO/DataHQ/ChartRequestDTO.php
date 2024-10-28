<?php

namespace App\DTO\DataHQ;

class ChartRequestDTO
{
    public string $xattr;
    public string $xattrType;

    public string $yattr;
    public string $yattrType;

    public string $filters;

    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->xattr = $data['xattr'];
        $dto->xattrType = $data['xattr_type'];
        $dto->yattr = $data['yattr'];
        $dto->yattrType = $data['yattr_type'];
        if (isset($data['filters'])) {
            $dto->filters = $data['filters'];
        } else {
            $dto->filters = '';
        }
        return $dto;
    }
}