<?php

namespace App\Markdown\Extensions\BracketExtension\Commands;

class SCommand implements CommandInterface
{
    /**
     * Get the command name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 's';
    }

    /**
     * Render the command with the given value.
     *
     * @param string $value
     * @return string
     */
    public function render(string $value): string
    {
        return "<div>s:{$value}</div>";
    }

    public function render2(string $command, string $value): string
    {
        return $this->render($value);
    }
}
