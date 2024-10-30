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

        $dto->xattr = self::getDataAttr($data, 'xattr');
        $dto->xattrType = self::getDataAttr($data, 'xattr_type');
        $dto->yattr = self::getDataAttr($data, 'yattr');
        $dto->yattrType = self::getDataAttr($data, 'yattr_type');
        $dto->filters = self::getDataAttr($data, 'filters');
        return $dto;
    }

    private static function getDataAttr($data, $attr)
    {
        if (isset($data[$attr])) {
            return $data[$attr];
        }

        return '';
    }
}