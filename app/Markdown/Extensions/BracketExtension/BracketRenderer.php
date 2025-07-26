<?php

namespace App\Markdown\Extensions\BracketExtension;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class BracketRenderer implements NodeRendererInterface
{
    private CommandRegistry $commandRegistry;

    public function __construct(CommandRegistry $commandRegistry)
    {
        $this->commandRegistry = $commandRegistry;
    }

    /**
     * @param Node $node
     * @param ChildNodeRendererInterface $childRenderer
     * @return string|null
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof BracketNode)) {
            return null;
        }

        $command = $node->getCommand();
        $value = $node->getValue();

        // Check if the command exists in the registry
        if ($this->commandRegistry->has($command)) {
            // Get the command and render it
            $commandHandler = $this->commandRegistry->get($command);
            return $commandHandler->render($value);
        }

        // If the command doesn't exist, return the original content
        return '{{'.$node->getContent().'}}';
    }
}
