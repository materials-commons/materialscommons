<?php

namespace App\Markdown\Extensions\BracketExtension;

use App\Markdown\Extensions\BracketExtension\Commands\SCommand;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

class BracketExtension implements ExtensionInterface
{
    /**
     * @param EnvironmentBuilderInterface $environment
     * @return void
     */
    public function register(EnvironmentBuilderInterface $environment): void
    {
        // Create the command registry
        $commandRegistry = new CommandRegistry();

        // Register the "s:" command
        $commandRegistry->register(new SCommand());

        // Register the parser
        $environment->addInlineParser(new BracketParser());

        // Register the renderer
        $environment->addRenderer(BracketNode::class, new BracketRenderer($commandRegistry));
    }
}
