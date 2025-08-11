<?php

namespace App\Markdown\Extensions\BracketExtension;

use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

class BracketParser implements InlineParserInterface
{
    /**
     * @return InlineParserMatch
     */
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex('\{\{([^}]+)\}\}');
    }

    /**
     * @param InlineParserContext $inlineContext
     * @return bool
     */
    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $matches = $inlineContext->getMatches();

        if (empty($matches[1])) {
            return false;
        }

        // Calculate the full length of the match (including the {{ and }})
        $fullMatch = $matches[0];
        $matchLength = strlen($fullMatch);

        // Advance the cursor by the length of the match
        $cursor->advanceBy($matchLength);

        $content = $matches[1];

        // Create a new BracketNode with the content
        $node = new BracketNode($content);

        // Add the node to the document
        $inlineContext->getContainer()->appendChild($node);

        return true;
    }
}
