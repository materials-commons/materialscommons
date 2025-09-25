<?php

namespace App\Markdown\Extensions\BracketExtension;

use League\CommonMark\Node\Inline\AbstractInline;
use League\CommonMark\Node\StringContainerInterface;

class BracketNode extends AbstractInline implements StringContainerInterface
{
    private string $content;
    private string $command;
    private string $value;

    public function __construct(string $content)
    {
        parent::__construct();
        $this->content = $content;

        // Extract command and value from content
        $parts = explode(':', $content, 2);
        $this->command = $parts[0];
        $this->value = $parts[1] ?? '';
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLiteral(): string
    {
        return $this->content;
    }

    public function setLiteral(string $literal): void
    {
        $this->content = $literal;
    }
}
