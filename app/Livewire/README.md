# EditableMarkdown Livewire Component

A Livewire component that enhances the UI by allowing users to view, edit, preview, and save Markdown content without page reloads.

## Features

- **View Mode**: Displays rendered Markdown content with an edit button
- **Edit Mode**: Provides a textarea for editing Markdown content
- **Live Preview**: Shows a real-time preview of the edited Markdown content
- **Toggle Preview**: Option to show or hide the preview panel
- **Save/Cancel**: Buttons to save changes or cancel and revert to the original content
- **Event Dispatching**: Dispatches events when content is saved for parent components to react
- **Custom Event Names**: Supports custom event names for different use cases

## Installation

The component is already installed as part of the Materials Commons application. No additional installation steps are required.

## Usage

### Basic Usage

```blade
<livewire:editable-markdown />
```

### With Initial Content

```blade
<livewire:editable-markdown 
    content="# Hello World

This is **Markdown** content."
/>
```

### With Custom Save Event

```blade
<livewire:editable-markdown 
    content="Your content here"
    save-event="descriptionSaved"
/>
```

### Listening for Save Events

```javascript
document.addEventListener('livewire:initialized', () => {
    Livewire.on('markdownSaved', (data) => {
        console.log('Markdown saved:', data.content);
        // Handle the saved content here
    });
});
```

## Component Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `content` | string | `''` | The initial Markdown content to display |
| `saveEvent` | string | `'markdownSaved'` | The event name to dispatch when content is saved |

## Component Methods

| Method | Description |
|--------|-------------|
| `toggleEdit()` | Switches between view and edit modes |
| `togglePreview()` | Shows or hides the preview panel in edit mode |
| `save()` | Saves the edited content and dispatches the save event |
| `cancel()` | Discards changes and reverts to the original content |

## Events

| Event | Parameters | Description |
|-------|------------|-------------|
| `markdownSaved` (default) | `{ content: string }` | Dispatched when content is saved |
| Custom event name | `{ content: string }` | If a custom `saveEvent` is provided, it will be dispatched instead |

## Examples

For complete examples of how to use the EditableMarkdown component, see the [example file](../../resources/views/examples/editable-markdown-example.blade.php).

## Implementation Details

The EditableMarkdown component uses the existing `x-markdown` Blade component for rendering Markdown content in both view mode and in the preview panel. This ensures consistency in how Markdown is rendered throughout the application.

## Testing

The component includes comprehensive tests that cover all its functionality. You can run the tests using PHPUnit:

```bash
php artisan test --filter=EditableMarkdownTest
```
