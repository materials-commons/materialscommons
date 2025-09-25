<?php

namespace App\Markdown\Extensions\QueryExtension;

use League\CommonMark\Node\Block\AbstractBlock;

class QueryBlock extends AbstractBlock
{
    private string $queryContent = '';
    private array $attributes = [];

    public function setQueryContent(string $content): void
    {
        $this->queryContent = $content;
    }

    public function getQueryContent(): string
    {
        return $this->queryContent;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
