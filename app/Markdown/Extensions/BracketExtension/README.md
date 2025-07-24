# Bracket Extension for CommonMark

This extension allows you to use custom commands in your Markdown content using the `{{command:value}}` syntax.

## Usage

The extension is automatically registered with the CommonMark environment in the `app/View/Components/Markdown.php` file. You can use it in your Markdown content like this:

```markdown
This is a test with {{s:hello}}
```

This will render as:

```html
<p>This is a test with <div>s:hello</div></p>
```

## Available Commands

Currently, the following commands are available:

- `s:` - Renders the value inside a div element: `<div>s:value</div>`

## Adding New Commands

To add a new command, follow these steps:

1. Create a new class in the `app/Markdown/Extensions/BracketExtension/Commands` directory that implements the `CommandInterface` interface:

```php
<?php

namespace App\Markdown\Extensions\BracketExtension\Commands;

class MyCommand implements CommandInterface
{
    /**
     * Get the command name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'mycommand';
    }

    /**
     * Render the command with the given value.
     *
     * @param string $value
     * @return string
     */
    public function render(string $value): string
    {
        return "<span class=\"my-command\">{$value}</span>";
    }
}
```

2. Register the command in the `BracketExtension` class:

```php
// In app/Markdown/Extensions/BracketExtension/BracketExtension.php

public function register(EnvironmentBuilderInterface $environment): void
{
    // Create the command registry
    $commandRegistry = new CommandRegistry();
    
    // Register the "s:" command
    $commandRegistry->register(new SCommand());
    
    // Register your new command
    $commandRegistry->register(new MyCommand());
    
    // Register the parser
    $environment->addInlineParser(new BracketParser());
    
    // Register the renderer
    $environment->addRenderer(BracketNode::class, new BracketRenderer($commandRegistry));
}
```

3. Use your new command in your Markdown content:

```markdown
This is a test with {{mycommand:hello}}
```

This will render as:

```html
<p>This is a test with <span class="my-command">hello</span></p>
```

## Extending the Renderer

If you need more complex rendering logic, you can extend the `BracketRenderer` class or create a new renderer that implements the `NodeRendererInterface` interface. Then register your new renderer in the `BracketExtension` class.

## Testing

The extension is tested in the `tests/Unit/Markdown/Extensions/BracketExtension/BracketExtensionTest.php` file. If you add a new command, you should add tests for it to ensure it works correctly.
