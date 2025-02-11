<?php

namespace App\Markdown\Extensions\Renderers\Inline;

use App\Markdown\Extensions\Renderers\MCImgSrc;
use Illuminate\Support\Str;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Node;
use League\CommonMark\Node\NodeIterator;
use League\CommonMark\Node\StringContainerInterface;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class MCImageRenderer implements NodeRendererInterface
{
    use MCImgSrc;

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        Image::assertInstanceOf($node);
        $attrs = $node->data->get('attributes');

        $src = urldecode($node->getUrl());
        if (!Str::startsWith($src, '/')) {
            return null;
        }

        $newSrcPath = $this->lookupPathByRouteContext($src);
        if (is_null($newSrcPath)) {
            return null;
        }

        $attrs['src'] = $newSrcPath;

        $attrs['alt'] = $this->getAltText($node);

        if (($title = $node->getTitle()) !== null) {
            $attrs['title'] = $title;
        }

        return new HtmlElement('img', $attrs, '', true);
    }

    private function getAltText(Image $node): string
    {
        $altText = '';

        foreach ((new NodeIterator($node)) as $n) {
            if ($n instanceof StringContainerInterface) {
                $altText .= $n->getLiteral();
            } elseif ($n instanceof Newline) {
                $altText .= "\n";
            }
        }

        return $altText;
    }
}