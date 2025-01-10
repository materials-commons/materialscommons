<?php

namespace App\DTO\DataHQOld;

use Livewire\Wireable;
use function blank;

class ChartRequestDTO implements Wireable
{
    public string $xattr;
    public string $xattrType;

    public string $yattr;
    public string $yattrType;

    public string $chartType;

    public string $filters;

    public static function fromArray(array $data): self
    {
        $dto = new self();

        $dto->xattr = self::getDataAttr($data, 'xattr');
        $dto->xattrType = self::getDataAttr($data, 'xattr_type');
        if (blank($dto->xattrType)) {
            $dto->xattrType = self::getDataAttr($data, 'xattrType');
        }
        $dto->yattr = self::getDataAttr($data, 'yattr');
        $dto->yattrType = self::getDataAttr($data, 'yattr_type');
        if (blank($dto->yattrType)) {
            $dto->yattrType = self::getDataAttr($data, 'yattrType');
        }
        $dto->filters = self::getDataAttr($data, 'filters');
        $dto->chartType = 'scatter';
        return $dto;
    }

    private static function getDataAttr($data, $attr)
    {
        if (isset($data[$attr])) {
            return $data[$attr];
        }

        return '';
    }


    public function toLivewire()
    {
        return [
            'xattr'     => $this->xattr,
            'xattrType' => $this->xattrType,
            'yattr'     => $this->yattr,
            'yattrType' => $this->yattrType,
            'chartType' => $this->chartType,
            'filters'   => $this->filters,
        ];
    }

    public static function fromLivewire($value)
    {
        $xattr = $value['xattr'];
        $xattrType = $value['xattrType'];
        $yattr = $value['yattr'];
        $yattrType = $value['yattrType'];

        $x = new static();
        $x->xattr = $xattr;
        $x->xattrType = $xattrType;
        $x->yattr = $yattr;
        $x->yattrType = $yattrType;
        $x->chartType = 'scatter';
        $x->filters = '';
        return $x;
    }
}