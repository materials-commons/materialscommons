<?php

namespace App\Markdown\Extensions\BracketExtension\Commands;

interface CommandInterface
{
    /**
     * Get the command name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Render the command with the given value.
     *
     * @param string $value
     * @return string
     */
    public function render(string $value): string;
}
