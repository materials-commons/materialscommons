<?php

namespace App\Markdown\Extensions\BracketExtension;

use App\Markdown\Extensions\BracketExtension\Commands\CommandInterface;

class CommandRegistry
{
    /**
     * @var CommandInterface[]
     */
    private array $commands = [];

    /**
     * Register a command.
     *
     * @param CommandInterface $command
     * @return void
     */
    public function register(CommandInterface $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    /**
     * Get a command by name.
     *
     * @param string $name
     * @return CommandInterface|null
     */
    public function get(string $name): ?CommandInterface
    {
        return $this->commands[$name] ?? null;
    }

    /**
     * Check if a command exists.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    /**
     * Get all registered commands.
     *
     * @return CommandInterface[]
     */
    public function all(): array
    {
        return $this->commands;
    }
}
