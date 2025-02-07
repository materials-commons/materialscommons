<?php

namespace App\Markdown\Extensions\MCFileDisplay;

use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

final class MCFileDisplayRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        ray($node);
        if ($node->firstChild() instanceof Text) {
            ray($node->firstChild()->getLiteral());
            ray(request()->route()->parameters());
        }
        return str("hello");
    }
}