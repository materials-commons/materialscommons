<?php

namespace App\Markdown\Extensions\QueryExtension;

use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

class QueryBlockStartParser implements BlockStartParserInterface
{
    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        $line = $cursor->getLine();

        // Check if line starts with ::: query
        if (preg_match('/^::: *query(?:\s+(.*))?$/', trim($line), $matches)) {
            $cursor->advanceToEnd();

            // Parse any attributes from the match
            $attributes = [];
            if (isset($matches[1])) {
                $attributeString = trim($matches[1]);
                if (!empty($attributeString)) {
                    // Simple key=value parsing
                    if (preg_match_all('/(\w+)=(["\']?)([^"\'\s]+)\2/', $attributeString, $attrMatches, PREG_SET_ORDER)) {
                        foreach ($attrMatches as $match) {
                            $attributes[$match[1]] = $match[3];
                        }
                    }
                }
            }

            return BlockStart::of(new QueryBlockParser($attributes));
        }

        return null;
    }
}
