<?php

namespace App\Markdown\Extensions\MCFileDisplay;

use League\CommonMark\Delimiter\DelimiterInterface;
use League\CommonMark\Delimiter\Processor\DelimiterProcessorInterface;
use League\CommonMark\Node\Inline\AbstractStringContainer;

class MCFileDisplayDelimiterProcessor implements DelimiterProcessorInterface
{

    public function getOpeningCharacter(): string
    {
        return '@';
    }

    public function getClosingCharacter(): string
    {
        return '@';
    }

    public function getMinLength(): int
    {
        return 2;
    }

    public function getDelimiterUse(DelimiterInterface $opener, DelimiterInterface $closer): int
    {
        if ($opener->getLength() > 2 && $closer->getLength() > 2) {
            return 0;
        }

        if ($opener->getLength() !== $closer->getLength()) {
            return 0;
        }

        return \min($opener->getLength(), $closer->getLength());
    }

    public function process(AbstractStringContainer $opener, AbstractStringContainer $closer, int $delimiterUse): void
    {
        $mcfileDisplay = new MCFileDisplay(\str_repeat('@', $delimiterUse));
        $tmp = $opener->next();
        while ($tmp !== null && $tmp !== $closer) {
            $next = $tmp->next();
            $mcfileDisplay->appendChild($tmp);
            $tmp = $next;
        }

        $opener->insertAfter($mcfileDisplay);
    }
}