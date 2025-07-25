<?php

namespace App\Markdown\Extensions\BracketExtension\Commands;

class DefaultCommand implements CommandInterface
{

    public function getName(): string
    {
        return '';
    }

    public function render(string $value): string
    {
        return '';
    }

    public function render2(string $command, string $value): string
    {

    }
}
