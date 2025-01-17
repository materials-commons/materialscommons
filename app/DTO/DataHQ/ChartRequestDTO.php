<?php

namespace App\DTO\DataHQ;

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

    public function __construct(string $xattr, string $xattrType, string $yattr, string $yattrType,
                                string $chartType = 'scatter', string $filters = '')
    {
        $this->xattr = $xattr;
        $this->xattrType = $xattrType;
        $this->yattr = $yattr;
        $this->yattrType = $yattrType;
        $this->chartType = $chartType;
        $this->filters = $filters;
    }

    public static function fromArray(array $data): self
    {
        $dto = new self('', '', '', '');

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

        return new static($xattr, $xattrType, $yattr, $yattrType);
    }
}