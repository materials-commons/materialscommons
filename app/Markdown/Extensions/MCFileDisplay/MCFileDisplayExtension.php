<?php

namespace App\Markdown\Extensions\MCFileDisplay;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class MCFileDisplayExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addDelimiterProcessor(new MCFileDisplayDelimiterProcessor());
        $environment->addRenderer(MCFileDisplay::class, new MCFileDisplayRenderer());
    }
}