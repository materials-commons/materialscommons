<?php

namespace App\Markdown\Extensions\QueryExtension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class QueryExtension implements ExtensionInterface
{
    private $context;
    private $contextId;

//    public function __construct($context, $contextId)
//    {
//        $this->context = $context;
//        $this->contextId = $contextId;
//    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addBlockStartParser(new QueryBlockStartParser(), 100)
            ->addRenderer(QueryBlock::class, new QueryRenderer(), 0);
//            ->addRenderer(QueryBlock::class, new QueryRenderer($this->context, $this->contextId), 0);
    }
}
