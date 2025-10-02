<?php

namespace App\Markdown\Extensions\QueryExtension;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;

class QueryBlockParser extends AbstractBlockContinueParser
{
    private QueryBlock $block;
    private array $content = [];
    private bool $finished = false;

    public function __construct(array $attributes = [])
    {
        $this->block = new QueryBlock();
        $this->block->setAttributes($attributes);
    }

    public function getBlock(): AbstractBlock
    {
        return $this->block;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        if ($this->finished) {
            return BlockContinue::none();
        }

        $line = trim($cursor->getLine());

        // Check for closing fence
        if ($line === ':::') {
            $this->finished = true;
            $cursor->advanceToEnd();
            return BlockContinue::finished();
        }

        // Add the line to our content
        $this->content[] = $cursor->getRemainder();
        $cursor->advanceToEnd();

        return BlockContinue::at($cursor);
    }

    public function isContainer(): bool
    {
        return false;
    }

    public function canContain(AbstractBlock $childBlock): bool
    {
        return false;
    }

    public function canHaveLazyContinuationLines(): bool
    {
        return false;
    }

    public function closeBlock(): void
    {
        // Set the final content
        $content = implode("\n", $this->content);
        $this->block->setQueryContent(trim($content));
    }
}
