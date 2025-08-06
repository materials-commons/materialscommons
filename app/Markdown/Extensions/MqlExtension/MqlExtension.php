<?php

namespace App\Markdown\Extensions\MqlExtension;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;

class MqlExtension implements ExtensionInterface
{
    /**
     * @param EnvironmentBuilderInterface $environment
     * @return void
     */
    public function register(EnvironmentBuilderInterface $environment): void
    {
        // Register the renderer for MQL code blocks
        $environment->addRenderer(FencedCode::class, new MqlRenderer(), 10);
    }
}
