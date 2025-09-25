<?php

namespace App\Markdown\Extensions\BracketExtension\Commands;

interface CommandInterface
{

    public function getName(): string;


    public function render(string $value): string;

    public function render2(string $command, string $value): string;
}
