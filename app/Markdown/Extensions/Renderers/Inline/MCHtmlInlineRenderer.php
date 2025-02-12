<?php

namespace App\Markdown\Extensions\Renderers\Inline;

use App\Markdown\Extensions\Renderers\MCImgSrc;
use Illuminate\Support\Str;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class MCHtmlInlineRenderer implements NodeRendererInterface
{
    use MCImgSrc;

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        $literalLowercased = Str::lower($node->getLiteral());
        if (Str::startsWith($literalLowercased, "<img")) {
            return $this->fixSrcTagIfNeeded($literalLowercased);
        }
        return null;
    }
}